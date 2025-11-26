"""
passive_sniffer_safe.py

- Tries packet sniffing with scapy (requires Npcap/WinPcap and Admin on Windows).
- If sniffing is unavailable, falls back to a driverless passive monitor:
    - monitors per-interface bytes/sec via psutil (detects traffic spikes)
    - polls 'arp -a' to detect ARP table churn (possible ARP/poisoning)
- Responds immediately to Ctrl+C and exits cleanly.
"""

import threading
import time
import sys
import subprocess
import platform
import os

# try imports (scapy optional)
try:
    from scapy.all import sniff, Ether, IP  # type: ignore
    SCAPY_AVAILABLE = True
except Exception:
    SCAPY_AVAILABLE = False

try:
    import psutil
except Exception:
    print("Please install psutil: pip install psutil")
    sys.exit(1)

# Global stop event
stop_event = threading.Event()

# Config
SNIF_TIMEOUT = 1  # seconds for scapy sniff loop (scapy stop uses stop_filter)
FALLBACK_POLL_INTERVAL = 1.0  # seconds between polls in fallback mode
TRAFFIC_SPIKE_THRESHOLD_MBPS = 5.0  # threshold bytes/sec (MB/s) to flag spike
ARP_CHURN_THRESHOLD = 5  # new arp entries within interval to flag churn


# ----------------------
# Utility helpers
# ----------------------
def print_banner():
    print("\n=== Passive Sniffer (safe mode) ===")
    print("Mode: scapy sniff if available & permitted, otherwise psutil+arp fallback")
    print("Press Ctrl+C to stop immediately\n")


def get_default_iface_ip():
    """Attempt to find a non-loopback IPv4 interface address"""
    addrs = psutil.net_if_addrs()
    for nic, addr_list in addrs.items():
        for a in addr_list:
            if a.family == psutil.AF_LINK:
                continue
            # psutil uses socket.AF_INET for IPv4
            try:
                if a.family.name == 'AF_INET' and not a.address.startswith("127."):
                    return nic, a.address
            except Exception:
                # fallback check:
                if hasattr(a, 'address') and isinstance(a.address, str) and not a.address.startswith("127."):
                    return nic, a.address
    # fallback to first AF_INET that's not loopback
    for nic, addr_list in addrs.items():
        for a in addr_list:
            if getattr(a, 'family', None) and getattr(a.family, 'name', '') == 'AF_INET':
                if not a.address.startswith("127."):
                    return nic, a.address
    return None, None


# ----------------------
# Scapy-based packet sniffing (best if available)
# ----------------------
def scapy_sniff_worker(iface_name=None):
    """
    Uses scapy.sniff in a loop. stop_filter checks stop_event so Ctrl+C works.
    Prints summary counters every few seconds.
    """
    print("[*] Scapy available. Attempting live packet capture.")
    counters = {
        'total': 0,
        'broadcast': 0,
        'arp': 0,
        'unknown_proto': 0
    }
    last_print = time.time()

    def pkt_cb(pkt):
        # Stop if requested
        if stop_event.is_set():
            return True  # stop_filter will stop if returns True

        counters['total'] += 1
        try:
            # Broadcast detection (Ethernet broadcast)
            if pkt.haslayer(Ether) and pkt[Ether].dst == "ff:ff:ff:ff:ff:ff":
                counters['broadcast'] += 1
            # ARP detection
            if pkt.haslayer('ARP') or pkt.summary().upper().find('ARP') != -1:
                counters['arp'] += 1
            # IP proto detection (unknown protocols)
            if pkt.haslayer(IP):
                proto = pkt[IP].proto
                if proto not in (6, 17, 1):  # TCP, UDP, ICMP
                    counters['unknown_proto'] += 1
        except Exception:
            pass

        # periodic print
        nonlocal last_print
        if time.time() - last_print >= 3.0:
            last_print = time.time()
            print(f"[scapy] total={counters['total']}, broadcast={counters['broadcast']}, arp={counters['arp']}, odd_proto={counters['unknown_proto']}")

    try:
        sniff(iface=iface_name, prn=pkt_cb, store=False, stop_filter=lambda x: stop_event.is_set(), timeout=None)
    except PermissionError:
        print("[!] Permission error: packet capture requires admin/root and Npcap/WinPcap on Windows.")
    except Exception as e:
        print(f"[!] Scapy sniffing failed: {e}")


# ----------------------
# Fallback monitor (no raw sockets; works without drivers)
# ----------------------
def arp_table_count():
    """
    Return set of IPs in ARP table using platform arp command.
    """
    try:
        if platform.system().lower().startswith("win"):
            out = subprocess.check_output(["arp", "-a"], text=True, stderr=subprocess.DEVNULL)
            lines = [l.strip() for l in out.splitlines() if l.strip()]
            ips = set()
            for line in lines:
                # Windows lines like: 10.0.0.1          00-11-22-33-44-55     dynamic
                parts = line.split()
                if len(parts) >= 2 and parts[0][0].isdigit():
                    ips.add(parts[0])
            return ips
        else:
            out = subprocess.check_output(["arp", "-n"], text=True, stderr=subprocess.DEVNULL)
            ips = set()
            for line in out.splitlines():
                parts = line.split()
                if len(parts) >= 1:
                    ip = parts[0]
                    if ip[0].isdigit():
                        ips.add(ip)
            return ips
    except Exception:
        return set()


def fallback_monitor_worker(iface):
    """
    Monitor interface bytes/sec via psutil and ARP table churn.
    Prints alerts if sudden spikes or ARP changes observed.
    """
    print("[*] Running fallback passive monitor (no driver/raw socket needed).")
    print(f"    Monitoring interface: {iface}\n")
    prev = psutil.net_io_counters(pernic=True).get(iface)
    if not prev:
        # pick first interface if requested not found
        nic_list = list(psutil.net_io_counters(pernic=True).keys())
        if not nic_list:
            print("[!] No network interfaces available via psutil.")
            return
        iface = nic_list[0]
        prev = psutil.net_io_counters(pernic=True).get(iface)

    prev_bytes = prev.bytes_recv + prev.bytes_sent
    prev_arp = arp_table_count()

    window = 0
    try:
        while not stop_event.is_set():
            time.sleep(FALLBACK_POLL_INTERVAL)
            cur = psutil.net_io_counters(pernic=True).get(iface)
            if not cur:
                continue
            cur_bytes = cur.bytes_recv + cur.bytes_sent
            delta = cur_bytes - prev_bytes
            mbps = (delta / 1024 / 1024) / FALLBACK_POLL_INTERVAL  # MB/s approx

            # ARP churn detect
            cur_arp = arp_table_count()
            new_arp = cur_arp - prev_arp
            removed_arp = prev_arp - cur_arp
            prev_bytes = cur_bytes
            prev_arp = cur_arp
            window += 1

            # print simple live stats every 2 loops
            if window % 2 == 0:
                print(f"[fallback] iface={iface} traffic={mbps:.2f} MB/s  arp_entries={len(cur_arp)}  new_arp={len(new_arp)} removed_arp={len(removed_arp)}")

            # alerts
            if mbps >= TRAFFIC_SPIKE_THRESHOLD_MBPS:
                print(f"⚠️ TRAFFIC SPIKE on {iface}: {mbps:.2f} MB/s (threshold {TRAFFIC_SPIKE_THRESHOLD_MBPS} MB/s)")

            if len(new_arp) >= ARP_CHURN_THRESHOLD:
                print(f"⚠️ ARP churn detected: {len(new_arp)} new ARP entries (possible ARP flood/poison)")

    except KeyboardInterrupt:
        stop_event.set()
        return


# ----------------------
# Main orchestrator
# ----------------------
def main():
    print_banner()

    iface, ip = get_default_iface_ip()
    if iface and ip:
        print(f"[i] Selected interface: {iface}  IP: {ip}")
    else:
        print("[i] Could not auto-detect interface; fallback will pick a default.")

    # If scapy available and user likely has capture capability, try scapy sniff
    use_scapy = SCAPY_AVAILABLE
    if use_scapy:
        print("[i] Scapy library available. If you have Admin + Npcap, the script will perform live capture.")
        print("    If scapy capture fails (permission), it will automatically fall back to safe mode.\n")
    else:
        print("[i] Scapy not available; using safe fallback monitor (no raw capture required).\n")

    threads = []
    try:
        if use_scapy:
            t = threading.Thread(target=scapy_sniff_worker, args=(iface,), daemon=True)
            t.start()
            threads.append(t)
            # Also run fallback monitor in parallel so you get both if scapy capture limited
            t2 = threading.Thread(target=fallback_monitor_worker, args=(iface,), daemon=True)
            t2.start()
            threads.append(t2)
        else:
            t = threading.Thread(target=fallback_monitor_worker, args=(iface,), daemon=True)
            t.start()
            threads.append(t)

        # Wait until Ctrl+C
        while not stop_event.is_set():
            time.sleep(0.2)

    except KeyboardInterrupt:
        stop_event.set()

    finally:
        print("\n[+] Stop requested — waiting for threads to finish...")
        stop_event.set()
        for t in threads:
            t.join(timeout=2.0)
        print("[+] Exited cleanly.")


if __name__ == "__main__":
    main()

import scapy.all as scapy
import sys
import json

# -------------------------------
# Live Status Printer
# -------------------------------
def status(msg):
    sys.stdout.write(f"\r{msg}   ")
    sys.stdout.flush()

# -------------------------------
# Get local network range
# -------------------------------
def get_local_ip():
    return scapy.get_if_addr(scapy.conf.iface)

def get_subnet():
    ip = get_local_ip()
    base = ip.rsplit(".", 1)[0]
    return f"{base}.0/24"

# -------------------------------
# Discover live hosts
# -------------------------------
def discover_hosts(network):
    arp = scapy.ARP(pdst=network)
    ether = scapy.Ether(dst="ff:ff:ff:ff:ff:ff")
    packet = ether / arp

    result = scapy.srp(packet, timeout=2, verbose=False)[0]

    hosts = []
    for sent, received in result:
        hosts.append(received.psrc)
    return hosts

# -------------------------------
# OS Fingerprinting (TTL + Window)
# -------------------------------
def fingerprint_os(ip):
    status(f"Probing {ip}")
    pkt = scapy.IP(dst=ip) / scapy.TCP(dport=80, flags="S")
    resp = scapy.sr1(pkt, timeout=1, verbose=False)

    if resp is None:
        return {"os_guess": "Offline/Filtered", "ttl": None, "window": None}

    ttl = resp.ttl
    window = resp.window

    if ttl >= 128:
        os_guess = "Windows"
    elif ttl >= 64:
        os_guess = "Linux / Android"
    elif ttl >= 255:
        os_guess = "Cisco / Network Device"
    else:
        os_guess = "Unknown"

    return {"os_guess": os_guess, "ttl": ttl, "window": window}

# -------------------------------
# Main
# -------------------------------
if __name__ == "__main__":
    try:
        network = get_subnet()
        hosts = discover_hosts(network)

        output = {"hosts": []}
        for ip in hosts:
            os_info = fingerprint_os(ip)
            output["hosts"].append({
                "ip": ip,
                **os_info
            })

        # Print JSON for Laravel
        print(json.dumps(output))

    except Exception as e:
        # Return error in JSON
        print(json.dumps({"error": str(e)}))

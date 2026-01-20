#!/usr/bin/env python3
"""
EDUCATIONAL NETWORK SNIFFING DEMONSTRATOR
Passive Network Sniffing - Confidentiality Attack Simulation
For Educational/Lab Use Only

Layer: Network / Data Link
Purpose: Demonstrate how passive sniffing works in controlled environments
"""

import sys
import os
import time
import json
from datetime import datetime
from typing import Dict, List, Optional
import argparse

# ASCII Banner
BANNER = """
╔══════════════════════════════════════════════════════════════╗
║       PASSIVE NETWORK SNIFFING DEMONSTRATOR                 ║
║           Educational Purposes Only                         ║
║           ⭐ Confidentiality Attack Simulation              ║
╚══════════════════════════════════════════════════════════════╝
"""


class PassiveSnifferSimulator:
    """
    Educational simulator for passive network sniffing concepts
    This does NOT actually sniff real network traffic
    """

    def __init__(self, interface: str = "eth0", duration: int = 30):
        self.interface = interface
        self.duration = duration
        self.packet_count = 0
        self.protocol_stats = {}
        self.vulnerable_protocols = []

        # Sample packet data for demonstration
        self.sample_packets = [
            {"src": "192.168.1.100", "dst": "93.184.216.34", "protocol": "HTTP", "port": 80, "encrypted": False,
             "data": "GET /login HTTP/1.1\nUser-Agent: Mozilla\nHost: example.com"},
            {"src": "192.168.1.101", "dst": "142.250.185.78", "protocol": "HTTPS", "port": 443, "encrypted": True,
             "data": "TLS 1.3 Handshake"},
            {"src": "192.168.1.102", "dst": "192.168.1.1", "protocol": "DNS", "port": 53, "encrypted": False,
             "data": "Query: google.com"},
            {"src": "192.168.1.103", "dst": "mail.example.com", "protocol": "SMTP", "port": 25, "encrypted": False,
             "data": "EHLO client\nMAIL FROM: user@example.com"},
            {"src": "192.168.1.104", "dst": "ftp.server.com", "protocol": "FTP", "port": 21, "encrypted": False,
             "data": "USER anonymous\nPASS email@example.com"},
            {"src": "192.168.1.105", "dst": "8.8.8.8", "protocol": "DNS", "port": 53, "encrypted": False,
             "data": "Query: bank.com"},
            {"src": "192.168.1.106", "dst": "172.217.16.110", "protocol": "HTTPS", "port": 443, "encrypted": True,
             "data": "Application Data"},
            {"src": "192.168.1.107", "dst": "chat.server.com", "protocol": "IRC", "port": 6667, "encrypted": False,
             "data": "PRIVMSG #channel :My password is 123456"},
        ]

    def display_introduction(self):
        """Display educational information about passive sniffing"""
        print("\n" + "=" * 70)
        print("🎯 PASSIVE NETWORK SNIFFING - EDUCATIONAL OVERVIEW")
        print("=" * 70)

        concepts = [
            "🔍 WHAT IT IS:",
            "   • Capturing packets WITHOUT modifying traffic",
            "   • Silent listening (no interception/alteration)",
            "   • Works on broadcast networks (Ethernet, WiFi)",
            "",
            "⚡ WHY IT'S FAMOUS:",
            "   • One of the oldest network attacks",
            "   • Used by attackers, ISPs, intelligence agencies",
            "   • Basis for Wireshark, tcpdump",
            "",
            "🎯 WHAT IT TESTS:",
            "   • Encryption enforcement (TLS/HTTPS usage)",
            "   • Plaintext protocol exposure",
            "   • Network segmentation effectiveness",
            "",
            "🛡️  DEFENSIVE COUNTERMEASURES:",
            "   • Use TLS/HTTPS everywhere",
            "   • Network segmentation/VLANs",
            "   • Encrypted DNS (DoH/DoT)",
            "   • VPNs for sensitive traffic",
        ]

        for line in concepts:
            print(line)
        print("=" * 70 + "\n")

    def simulate_sniffing(self):
        """Simulate packet capture process"""
        print(f"[*] Starting passive sniffing simulation on {self.interface}")
        print(f"[*] Duration: {self.duration} seconds")
        print("[*] Mode: PROMISCUOUS (capturing all traffic on network segment)")
        print("-" * 60)

        for i in range(self.duration):
            if i % 5 == 0 and i < len(self.sample_packets):
                self.process_packet(self.sample_packets[i])
            time.sleep(1)

            if i % 10 == 0:
                self.display_live_stats()

    def process_packet(self, packet: Dict):
        """Process a simulated packet"""
        self.packet_count += 1

        # Update protocol statistics
        proto = packet["protocol"]
        self.protocol_stats[proto] = self.protocol_stats.get(proto, 0) + 1

        # Check for vulnerabilities
        if not packet["encrypted"] and packet["protocol"] in ["HTTP", "FTP", "SMTP", "IRC"]:
            if packet not in self.vulnerable_protocols:
                self.vulnerable_protocols.append(packet)
                self.flag_vulnerability(packet)

        # Display packet info
        timestamp = datetime.now().strftime("%H:%M:%S.%f")[:-3]
        print(f"[{timestamp}] Packet #{self.packet_count:03d}")
        print(f"    Source: {packet['src']}:{packet['port']}")
        print(f"    Dest:   {packet['dst']}:{packet['port']}")
        print(f"    Proto:  {packet['protocol']}")
        print(f"    Encrypted: {'✅ Yes' if packet['encrypted'] else '❌ No'}")

        if not packet["encrypted"]:
            print(f"    Data:   {packet['data'][:50]}...")
        print()

    def flag_vulnerability(self, packet: Dict):
        """Flag security vulnerabilities found in packets"""
        print("!" * 60)
        print("🚨 SECURITY VULNERABILITY DETECTED!")
        print(f"   Protocol: {packet['protocol']}")
        print(f"   Traffic:  {packet['src']} → {packet['dst']}:{packet['port']}")

        risks = {
            "HTTP": "Credentials and data transmitted in CLEAR TEXT",
            "FTP": "Passwords and files transmitted in CLEAR TEXT",
            "SMTP": "Emails transmitted in CLEAR TEXT",
            "IRC": "Chat messages transmitted in CLEAR TEXT",
            "DNS": "Queries reveal browsing history",
        }

        if packet["protocol"] in risks:
            print(f"   Risk:     {risks[packet['protocol']]}")

        print("!" * 60 + "\n")

    def display_live_stats(self):
        """Display live statistics"""
        print("\n" + "-" * 60)
        print("📊 LIVE SNIFFING STATISTICS")
        print("-" * 60)
        print(f"Total Packets Captured: {self.packet_count}")
        print("\nProtocol Distribution:")
        for proto, count in self.protocol_stats.items():
            percentage = (count / self.packet_count * 100) if self.packet_count > 0 else 0
            print(f"  {proto:10s}: {count:3d} packets ({percentage:5.1f}%)")
        print("-" * 60 + "\n")

    def generate_report(self):
        """Generate educational security report"""
        print("\n" + "=" * 70)
        print("📑 EDUCATIONAL SECURITY ASSESSMENT REPORT")
        print("=" * 70)

        total_packets = self.packet_count
        encrypted_count = sum(1 for p in self.sample_packets if p["encrypted"])

        print(f"\n📈 SUMMARY STATISTICS:")
        print(f"   Total packets analyzed: {total_packets}")
        print(f"   Protocols detected: {len(self.protocol_stats)}")

        print(f"\n🔒 ENCRYPTION ANALYSIS:")
        encryption_rate = (encrypted_count / len(self.sample_packets) * 100) if self.sample_packets else 0
        print(f"   Encrypted traffic: {encryption_rate:.1f}%")

        if encryption_rate < 70:
            print("   ⚠️  WARNING: Low encryption rate detected!")

        print(f"\n⚠️  VULNERABILITIES FOUND: {len(self.vulnerable_protocols)}")
        if self.vulnerable_protocols:
            print("   Vulnerable protocols in use:")
            for vuln in self.vulnerable_protocols[:3]:  # Show first 3
                print(f"   • {vuln['protocol']}: {vuln['src']} → {vuln['dst']}")
            if len(self.vulnerable_protocols) > 3:
                print(f"   ... and {len(self.vulnerable_protocols) - 3} more")

        print(f"\n🛡️  RECOMMENDED ACTIONS:")
        recommendations = [
            "1. Enforce HTTPS/TLS for all web traffic",
            "2. Replace FTP with SFTP or FTPS",
            "3. Use encrypted SMTP (SMTPS, STARTTLS)",
            "4. Implement network segmentation/VLANs",
            "5. Use VPNs for remote access",
            "6. Deploy encrypted DNS (DoH/DoT)",
            "7. Monitor for unauthorized sniffing",
        ]

        for rec in recommendations:
            print(f"   {rec}")

        print("\n" + "=" * 70)


def check_environment():
    """Check if script is running in appropriate environment"""
    print("[*] Performing environment check...")

    # Check if running as root (would be required for real sniffing)
    if os.geteuid() == 0:
        print("[!] Running with root privileges")
        print("[!] WARNING: Real sniffing would be possible")
    else:
        print("[✓] Running without root privileges (safe)")

    # Check platform
    platform = sys.platform
    print(f"[*] Platform: {platform}")

    return True


def main():
    parser = argparse.ArgumentParser(
        description="Educational Passive Network Sniffer Simulator",
        epilog="For educational purposes only. Use in controlled lab environments."
    )

    parser.add_argument("-i", "--interface", default="eth0",
                        help="Network interface to simulate (default: eth0)")
    parser.add_argument("-t", "--time", type=int, default=30,
                        help="Duration in seconds (default: 30)")
    parser.add_argument("-o", "--output", help="Save report to file")

    args = parser.parse_args()

    # Display banner
    print(BANNER)

    # Check environment
    check_environment()

    # Create simulator
    sniffer = PassiveSnifferSimulator(args.interface, args.time)

    # Display educational info
    sniffer.display_introduction()

    input("Press Enter to start simulation...\n")

    try:
        # Run simulation
        sniffer.simulate_sniffing()

        # Generate report
        sniffer.generate_report()

        # Save report if requested
        if args.output:
            with open(args.output, 'w') as f:
                import io
                from contextlib import redirect_stdout

                output = io.StringIO()
                with redirect_stdout(output):
                    sniffer.generate_report()
                f.write(output.getvalue())
            print(f"\n[✓] Report saved to {args.output}")

    except KeyboardInterrupt:
        print("\n\n[!] Simulation interrupted by user")
        sniffer.generate_report()
        sys.exit(0)

    print("\n" + "=" * 70)
    print("✅ Simulation complete")
    print("=" * 70)
    print("\n💡 Remember: This was a SIMULATION for educational purposes.")
    print("   Real network sniffing requires proper authorization.")
    print("   Always use encryption to protect your network traffic!")


if __name__ == "__main__":
    main()
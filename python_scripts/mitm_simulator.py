#!/usr/bin/env python3
"""
SAFE MITM Simulation for Laravel App
Simulates attack behavior without real network manipulation
"""

import json
import random
import socket
import subprocess
import re
import platform
from datetime import datetime
from typing import Dict, List, Any


class SafeMITMSimulator:
    def __init__(self):
        # Configuration
        self.simulation_params = {
            "max_intercepted_packets": 300,
            "max_credentials": 5,
            "network_scan_intensity": 0.7  # 0-1 scale
        }
        
        # ANSI colors for output
        self.colors = {
            'RED': '\033[91m',
            'GREEN': '\033[92m',
            'YELLOW': '\033[93m',
            'BLUE': '\033[94m',
            'MAGENTA': '\033[95m',
            'CYAN': '\033[96m',
            'RESET': '\033[0m'
        }

    def safe_network_scan(self) -> Dict[str, Any]:
        """Safely simulate network scanning without real attacks"""
        print(f"{self.colors['BLUE']}[*] Starting SAFE network scan simulation...{self.colors['RESET']}")
        
        try:
            # Get local IP safely
            local_ip = socket.gethostbyname(socket.gethostname())
            
            # Simulate device discovery based on network conditions
            base_device_count = random.randint(3, 12)
            
            # Add some variation based on time/network
            hour_factor = datetime.now().hour
            if 9 <= hour_factor <= 17:  # Business hours
                device_count = base_device_count + random.randint(2, 8)
            else:
                device_count = base_device_count + random.randint(0, 3)
            
            # Simulated device types
            device_types = [
                "Windows PC", "MacBook", "iPhone", "Android Phone", 
                "Linux Server", "Printer", "IoT Device", "Router"
            ]
            
            # Generate simulated devices
            simulated_devices = []
            for i in range(device_count):
                device = {
                    "ip": f"192.168.1.{100 + i}",
                    "mac": ":".join(f"{random.randint(0, 255):02x}" for _ in range(6)),
                    "type": random.choice(device_types),
                    "vulnerable": random.choice([True, False, False])  # 1/3 chance
                }
                simulated_devices.append(device)
            
            print(f"{self.colors['GREEN']}[+] Simulated network scan complete{self.colors['RESET']}")
            print(f"{self.colors['CYAN']}[*] Found {device_count} devices (simulated){self.colors['RESET']}")
            
            return {
                "device_count": device_count,
                "local_ip": local_ip,
                "devices": simulated_devices,
                "scan_timestamp": datetime.now().isoformat()
            }
            
        except Exception as e:
            print(f"{self.colors['YELLOW']}[!] Simulation using fallback values: {e}{self.colors['RESET']}")
            return {
                "device_count": random.randint(5, 15),
                "local_ip": "192.168.1.100",
                "devices": [],
                "scan_timestamp": datetime.now().isoformat()
            }

    def simulate_mitm_attack(self, network_info: Dict) -> Dict[str, Any]:
        """Simulate MITM attack results without actual interception"""
        print(f"{self.colors['MAGENTA']}[*] Simulating MITM attack behavior...{self.colors['RESET']}")
        
        device_count = network_info["device_count"]
        
        # Calculate simulated results
        base_packets = random.randint(50, 150)
        
        # Scale based on device count
        device_factor = min(device_count * 15, 200)
        intercepted_packets = base_packets + device_factor
        
        # Credentials exposed calculation
        vulnerable_devices = sum(1 for d in network_info.get("devices", []) if d.get("vulnerable", False))
        exposed_credentials = random.randint(0, min(vulnerable_devices, 3))
        
        # Risk level calculation
        if exposed_credentials > 2:
            risk_level = "High"
        elif exposed_credentials > 0:
            risk_level = "Medium"
        else:
            risk_level = "Low"
        
        # Simulated packet types (for educational display)
        packet_types = {
            "HTTP": random.randint(20, 100),
            "HTTPS": random.randint(10, 80),
            "DNS": random.randint(15, 60),
            "Email": random.randint(5, 30),
            "File Transfer": random.randint(0, 20)
        }
        
        print(f"{self.colors['GREEN']}[+] MITM simulation complete!{self.colors['RESET']}")
        
        return {
            "intercepted_packets": intercepted_packets,
            "exposed_credentials": exposed_credentials,
            "risk_level": risk_level,
            "packet_types": packet_types,
            "vulnerable_devices_found": vulnerable_devices,
            "simulation_duration": f"{random.randint(3, 8)} seconds",
            "timestamp": datetime.now().isoformat()
        }

    def generate_educational_report(self, network_info: Dict, attack_results: Dict) -> Dict[str, Any]:
        """Generate educational report about MITM risks"""
        report = {
            "educational_insights": [
                f"A real MITM attack on this network could intercept approximately {attack_results['intercepted_packets']} packets.",
                f"{attack_results['exposed_credentials']} sets of credentials would be at risk.",
                "Using HTTPS (SSL/TLS) would protect {:.0%} of the traffic.".format(
                    attack_results['packet_types']['HTTPS'] / sum(attack_results['packet_types'].values())
                ) if sum(attack_results['packet_types'].values()) > 0 else "All traffic appears unencrypted.",
                "Network segmentation and VPN usage would mitigate this risk."
            ],
            "recommendations": [
                "Use HTTPS everywhere",
                "Implement network segmentation",
                "Use VPN for sensitive operations",
                "Enable certificate pinning",
                "Monitor for unusual ARP traffic"
            ],
            "simulation_parameters": {
                "network_size": network_info["device_count"],
                "encryption_level": "Mixed" if attack_results['packet_types']['HTTPS'] > 20 else "Low",
                "attack_success_probability": "High" if attack_results['risk_level'] == "High" else "Medium"
            }
        }
        
        return report

    def run_simulation(self) -> str:
        """Main method to run the safe simulation and return JSON results"""
        try:
            print(f"{self.colors['CYAN']}{'='*60}{self.colors['RESET']}")
            print(f"{self.colors['CYAN']}[*] BASTION SECURITY - SAFE MITM SIMULATION{self.colors['RESET']}")
            print(f"{self.colors['CYAN']}[*] Educational Purpose Only{self.colors['RESET']}")
            print(f"{self.colors['CYAN']}{'='*60}{self.colors['RESET']}\n")
            
            # Step 1: Safe network scan
            network_info = self.safe_network_scan()
            
            # Step 2: Simulate MITM attack
            attack_results = self.simulate_mitm_attack(network_info)
            
            # Step 3: Generate educational report
            educational_report = self.generate_educational_report(network_info, attack_results)
            
            # Combine all results
            full_results = {
                "simulation_type": "MITM",
                "status": "completed",
                "network_info": network_info,
                "attack_results": attack_results,
                "educational_report": educational_report,
                "metadata": {
                    "simulator_version": "1.0",
                    "execution_time": datetime.now().isoformat(),
                    "is_real_attack": False,
                    "purpose": "cybersecurity_education",
                    # CHART DATA ADDED HERE
                    "chart_data": {
                        "traffic_over_time": {
                            "labels": ["00:00", "04:00", "08:00", "12:00", "16:00", "20:00"],
                            "data": [
                                random.randint(20, 50),
                                random.randint(10, 30),
                                random.randint(40, 80),
                                random.randint(60, 100),
                                random.randint(70, 120),
                                random.randint(50, 90)
                            ]
                        },
                        "protocol_distribution": {
                            "labels": ["HTTP", "HTTPS", "DNS", "Email", "File Transfer"],
                            "data": [
                                attack_results['packet_types']['HTTP'],
                                attack_results['packet_types']['HTTPS'],
                                attack_results['packet_types']['DNS'],
                                attack_results['packet_types']['Email'],
                                attack_results['packet_types']['File Transfer']
                            ],
                            "colors": ["#ef4444", "#10b981", "#3b82f6", "#8b5cf6", "#f59e0b"]
                        }
                    }
                }
            }
            
            print(f"\n{self.colors['GREEN']}{'='*60}{self.colors['RESET']}")
            print(f"{self.colors['GREEN']}[✓] SIMULATION COMPLETE - SAFE MODE{self.colors['RESET']}")
            print(f"{self.colors['GREEN']}[✓] No real attacks performed{self.colors['RESET']}")
            print(f"{self.colors['GREEN']}{'='*60}{self.colors['RESET']}")
            
            return json.dumps(full_results, indent=2)
            
        except Exception as e:
            error_result = {
                "error": str(e),
                "status": "failed",
                "simulation_type": "MITM",
                "fallback_results": {
                    "intercepted_packets": random.randint(80, 300),
                    "exposed_credentials": random.randint(0, 3),
                    "risk_level": random.choice(["Low", "Medium", "High"])
                }
            }
            return json.dumps(error_result)


def main():
    """Entry point for standalone execution"""
    simulator = SafeMITMSimulator()
    results = simulator.run_simulation()
    print("\n" + results)


if __name__ == "__main__":
    main()
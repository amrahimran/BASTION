#!/usr/bin/env python3
"""
BASTION SECURITY - DDoS Attack Simulator
Advanced simulation showing DDoS attack patterns and defense mechanisms
Educational purpose only - No real attacks performed
"""

import json
import random
import time
import sys
from datetime import datetime
from typing import Dict, List, Any
import math


class AdvancedDDoSSimulator:
    def __init__(self, mode="Medium", target="Website"):
        self.mode = mode
        self.target = target
        self.simulation_id = f"DDoS-{datetime.now().strftime('%Y%m%d-%H%M%S')}"
        
        # Attack patterns based on mode
        self.attack_profiles = {
            "Low": {
                "base_rate": 100,
                "peak_rate": 300,
                "duration": 60,
                "attack_types": ["HTTP Flood", "Slowloris"],
                "botnet_size": 500
            },
            "Medium": {
                "base_rate": 300,
                "peak_rate": 1000,
                "duration": 300,
                "attack_types": ["UDP Flood", "ICMP Flood", "HTTP Flood"],
                "botnet_size": 5000
            },
            "High": {
                "base_rate": 1000,
                "peak_rate": 5000,
                "duration": 600,
                "attack_types": ["DNS Amplification", "NTP Amplification", "SYN Flood"],
                "botnet_size": 25000
            }
        }
        
        # Target vulnerabilities
        self.target_vulnerabilities = {
            "Website": {"bandwidth": 100, "defenses": ["CDN", "WAF"], "recovery_time": 60},
            "API Server": {"bandwidth": 50, "defenses": ["Rate Limiting", "API Gateway"], "recovery_time": 120},
            "Game Server": {"bandwidth": 200, "defenses": ["DDoS Protection", "Anycast"], "recovery_time": 30},
            "DNS Server": {"bandwidth": 500, "defenses": ["Anycast DNS", "Filtering"], "recovery_time": 180}
        }
        
        # Colors for console output
        self.colors = {
            'RED': '\033[91m',
            'GREEN': '\033[92m',
            'YELLOW': '\033[93m',
            'BLUE': '\033[94m',
            'MAGENTA': '\033[95m',
            'CYAN': '\033[96m',
            'WHITE': '\033[97m',
            'GRAY': '\033[90m',
            'BOLD': '\033[1m',
            'RESET': '\033[0m'
        }

    def simulate_attack_pattern(self) -> Dict[str, Any]:
        """Generate realistic DDoS attack pattern"""
        profile = self.attack_profiles[self.mode]
        target_info = self.target_vulnerabilities.get(self.target, self.target_vulnerabilities["Website"])
        
        print(f"{self.colors['CYAN']}[*] Simulating {self.mode} intensity DDoS attack on {self.target}...{self.colors['RESET']}")
        
        # Generate attack timeline with realistic patterns
        timeline = []
        current_time = 0
        phase = "reconnaissance"
        
        # Phase 1: Reconnaissance (10% of time)
        recon_duration = profile['duration'] * 0.1
        timeline.append({
            "phase": "reconnaissance",
            "duration": recon_duration,
            "request_rate": profile['base_rate'] * 0.1,
            "description": "Scanning target for vulnerabilities"
        })
        current_time += recon_duration
        
        # Phase 2: Ramp-up (20% of time)
        ramp_duration = profile['duration'] * 0.2
        ramp_steps = 10
        for i in range(ramp_steps):
            rate = profile['base_rate'] + (i * (profile['peak_rate'] - profile['base_rate']) / ramp_steps)
            timeline.append({
                "phase": "ramp_up",
                "duration": ramp_duration / ramp_steps,
                "request_rate": rate,
                "description": f"Gradually increasing attack intensity ({i+1}/{ramp_steps})"
            })
        current_time += ramp_duration
        
        # Phase 3: Sustained attack (50% of time)
        sustain_duration = profile['duration'] * 0.5
        timeline.append({
            "phase": "sustained",
            "duration": sustain_duration,
            "request_rate": profile['peak_rate'],
            "description": "Full-scale attack maintaining peak traffic"
        })
        current_time += sustain_duration
        
        # Phase 4: Decay (20% of time)
        decay_duration = profile['duration'] * 0.2
        decay_steps = 10
        for i in range(decay_steps):
            rate = profile['peak_rate'] - (i * (profile['peak_rate'] - profile['base_rate']) / decay_steps)
            timeline.append({
                "phase": "decay",
                "duration": decay_duration / decay_steps,
                "request_rate": rate,
                "description": f"Gradually decreasing attack intensity ({i+1}/{decay_steps})"
            })
        
        # Calculate impact
        total_requests = sum([t["duration"] * t["request_rate"] for t in timeline])
        target_capacity = target_info['bandwidth'] * 1000  # Convert to requests/second capacity
        
        # Determine attack success
        success_ratio = profile['peak_rate'] / target_capacity
        if success_ratio > 2:
            impact_level = "Catastrophic"
            downtime = profile['duration']
            recovery_time = target_info['recovery_time'] * 2
        elif success_ratio > 1:
            impact_level = "Severe"
            downtime = profile['duration'] * 0.8
            recovery_time = target_info['recovery_time']
        elif success_ratio > 0.5:
            impact_level = "Moderate"
            downtime = profile['duration'] * 0.3
            recovery_time = target_info['recovery_time'] * 0.5
        else:
            impact_level = "Minimal"
            downtime = profile['duration'] * 0.1
            recovery_time = target_info['recovery_time'] * 0.2
        
        return {
            "timeline": timeline,
            "total_requests": int(total_requests),
            "peak_request_rate": profile['peak_rate'],
            "average_request_rate": int(total_requests / profile['duration']),
            "attack_duration": profile['duration'],
            "botnet_size": profile['botnet_size'],
            "attack_types": profile['attack_types'],
            "target_capacity": target_capacity,
            "success_ratio": success_ratio,
            "impact_level": impact_level,
            "estimated_downtime": downtime,
            "recovery_time": recovery_time
        }

    def generate_network_traffic_data(self, attack_data: Dict) -> Dict[str, Any]:
        """Generate detailed network traffic simulation"""
        print(f"{self.colors['MAGENTA']}[*] Analyzing network traffic patterns...{self.colors['RESET']}")
        
        # Generate traffic composition
        traffic_composition = {
            "legitimate_traffic": random.randint(20, 50),
            "attack_traffic": 100 - random.randint(20, 50)
        }
        
        # Protocol breakdown
        protocols = {
            "TCP/SYN": random.randint(30, 60),
            "UDP": random.randint(20, 40),
            "ICMP": random.randint(5, 15),
            "HTTP/HTTPS": random.randint(10, 30),
            "DNS": random.randint(5, 10)
        }
        
        # Source countries simulation
        countries = ["USA", "China", "Russia", "Brazil", "Germany", "India", "UK", "France", "Japan", "Australia"]
        source_distribution = {}
        remaining = 100
        for i, country in enumerate(countries):
            if i == len(countries) - 1:
                source_distribution[country] = remaining
            else:
                percent = random.randint(5, min(25, remaining))
                source_distribution[country] = percent
                remaining -= percent
        
        # Time-series data for charts
        time_series = []
        for minute in range(0, attack_data['attack_duration'] // 60 + 1):
            if minute < 5:  # Ramp-up phase
                rate = attack_data['peak_request_rate'] * (minute / 5)
            elif minute < 25:  # Peak phase
                rate = attack_data['peak_request_rate'] * random.uniform(0.9, 1.1)
            else:  # Decay phase
                rate = attack_data['peak_request_rate'] * max(0.1, (35 - minute) / 10)
            
            time_series.append({
                "minute": minute,
                "requests_per_second": int(rate),
                "connections": int(rate * random.uniform(0.5, 2)),
                "bandwidth_mbps": rate * random.uniform(0.1, 0.5)
            })
        
        return {
            "traffic_composition": traffic_composition,
            "protocols": protocols,
            "source_distribution": source_distribution,
            "time_series": time_series,
            "bot_ips_detected": random.randint(1000, attack_data['botnet_size']),
            "legitimate_users_affected": random.randint(50, 500),
            "packet_loss_percentage": random.uniform(10, 90) if attack_data['success_ratio'] > 0.7 else random.uniform(1, 10)
        }

    def calculate_risk_assessment(self, attack_data: Dict, traffic_data: Dict) -> Dict[str, Any]:
        """Calculate comprehensive risk assessment"""
        print(f"{self.colors['YELLOW']}[*] Calculating risk assessment...{self.colors['RESET']}")
        
        # Risk factors
        risk_factors = {
            "traffic_volume": min(100, attack_data['success_ratio'] * 50),
            "attack_complexity": random.randint(60, 90) if len(attack_data['attack_types']) > 2 else random.randint(30, 60),
            "target_vulnerability": random.randint(40, 80),
            "defense_capability": random.randint(20, 80),
            "recovery_difficulty": min(100, attack_data['recovery_time'] / 10)
        }
        
        # Overall risk score (0-100)
        risk_score = sum(risk_factors.values()) / len(risk_factors)
        
        # Risk level
        if risk_score >= 75:
            risk_level = "Critical"
            color = "RED"
        elif risk_score >= 50:
            risk_level = "High"
            color = "ORANGE"
        elif risk_score >= 25:
            risk_level = "Medium"
            color = "YELLOW"
        else:
            risk_level = "Low"
            color = "GREEN"
        
        # Financial impact estimation (in USD)
        downtime_cost = attack_data['estimated_downtime'] / 60 * random.randint(1000, 10000)
        recovery_cost = attack_data['recovery_time'] / 60 * random.randint(500, 5000)
        reputation_cost = random.randint(5000, 50000)
        total_cost = downtime_cost + recovery_cost + reputation_cost
        
        return {
            "risk_score": round(risk_score, 1),
            "risk_level": risk_level,
            "risk_color": color,
            "risk_factors": risk_factors,
            "financial_impact": {
                "downtime_cost": round(downtime_cost, 2),
                "recovery_cost": round(recovery_cost, 2),
                "reputation_cost": round(reputation_cost, 2),
                "total_estimated_cost": round(total_cost, 2)
            },
            "recommendations": [
                "Implement DDoS mitigation service",
                "Configure rate limiting on all endpoints",
                "Use CDN for static content delivery",
                "Enable Anycast DNS for distributed resolution",
                "Deploy Web Application Firewall (WAF)",
                "Establish incident response plan",
                "Monitor traffic patterns for anomalies",
                "Regularly update security patches"
            ]
        }

    def generate_educational_insights(self, attack_data: Dict, traffic_data: Dict, risk_data: Dict) -> Dict[str, Any]:
        """Generate educational content about DDoS attacks"""
        insights = [
            f"This {self.mode.lower()}-intensity DDoS simulation generated {attack_data['total_requests']:,} total requests.",
            f"Attack peaked at {attack_data['peak_request_rate']:,} requests/second, overwhelming the target's capacity of {attack_data['target_capacity']:,} req/sec.",
            f"Traffic analysis shows {traffic_data['traffic_composition']['attack_traffic']}% malicious traffic mixed with legitimate users.",
            f"The attack originated from {len(traffic_data['source_distribution'])} countries, making traceback difficult.",
            f"Estimated financial impact: ${risk_data['financial_impact']['total_estimated_cost']:,.2f} including downtime and recovery.",
            f"Without proper defenses, services could be unavailable for up to {attack_data['estimated_downtime']/60:.1f} minutes.",
            f"DDoS attacks often use amplification techniques (DNS, NTP) to multiply attack power with minimal resources.",
            "Modern DDoS attacks can exceed 1 Tbps, requiring specialized cloud-based mitigation services.",
            "Early detection and traffic filtering are crucial to minimize service disruption.",
            "Having a prepared incident response plan can reduce recovery time by up to 70%."
        ]
        
        return {
            "insights": insights,
            "attack_mechanics": [
                "Botnets: Networks of compromised devices used to launch coordinated attacks",
                "Amplification: Exploiting protocols that generate large responses to small requests",
                "Flooding: Overwhelming targets with more requests than they can handle",
                "Protocol Exploitation: Targeting weaknesses in network protocols like TCP SYN"
            ],
            "defense_strategies": [
                "Absorption: Using large network capacity to absorb attack traffic",
                "Scrubbing: Filtering malicious traffic before it reaches the target",
                "Diversion: Redirecting attack traffic to mitigation centers",
                "Hardening: Strengthening infrastructure against specific attack types"
            ]
        }

    def run_simulation(self) -> str:
        """Main simulation execution"""
        try:
            print(f"{self.colors['CYAN']}{'='*70}{self.colors['RESET']}")
            print(f"{self.colors['BOLD']}{self.colors['WHITE']}[*] BASTION SECURITY - ADVANCED DDoS SIMULATION{self.colors['RESET']}")
            print(f"{self.colors['CYAN']}{'='*70}{self.colors['RESET']}")
            print(f"{self.colors['GRAY']}[*] Simulation ID: {self.simulation_id}{self.colors['RESET']}")
            print(f"{self.colors['GRAY']}[*] Attack Mode: {self.mode}{self.colors['RESET']}")
            print(f"{self.colors['GRAY']}[*] Target: {self.target}{self.colors['RESET']}")
            print(f"{self.colors['CYAN']}{'='*70}{self.colors['RESET']}\n")
            
            time.sleep(1)
            
            # Step 1: Simulate attack pattern
            attack_data = self.simulate_attack_pattern()
            print(f"{self.colors['GREEN']}[✓] Attack pattern simulation complete{self.colors['RESET']}")
            
            time.sleep(0.5)
            
            # Step 2: Generate network traffic data
            traffic_data = self.generate_network_traffic_data(attack_data)
            print(f"{self.colors['GREEN']}[✓] Network traffic analysis complete{self.colors['RESET']}")
            
            time.sleep(0.5)
            
            # Step 3: Calculate risk assessment
            risk_data = self.calculate_risk_assessment(attack_data, traffic_data)
            print(f"{self.colors['GREEN']}[✓] Risk assessment complete{self.colors['RESET']}")
            
            time.sleep(0.5)
            
            # Step 4: Generate educational insights
            educational_data = self.generate_educational_insights(attack_data, traffic_data, risk_data)
            print(f"{self.colors['GREEN']}[✓] Educational content generated{self.colors['RESET']}")
            
            # Compile final results
            results = {
                "simulation_type": "DDOS",
                "status": "completed",
                "simulation_id": self.simulation_id,
                "attack_parameters": {
                    "mode": self.mode,
                    "target": self.target,
                    "attack_types": attack_data["attack_types"]
                },
                "attack_results": {
                    "total_requests": attack_data["total_requests"],
                    "peak_request_rate": attack_data["peak_request_rate"],
                    "average_request_rate": attack_data["average_request_rate"],
                    "duration": attack_data["attack_duration"],
                    "botnet_size": attack_data["botnet_size"],
                    "impact_level": attack_data["impact_level"],
                    "estimated_downtime": attack_data["estimated_downtime"],
                    "recovery_time": attack_data["recovery_time"]
                },
                "traffic_analysis": traffic_data,
                "risk_assessment": risk_data,
                "educational_insights": educational_data,
                "chart_data": {
                    "timeline": [
                        {"minute": ts["minute"], "requests": ts["requests_per_second"]} 
                        for ts in traffic_data["time_series"]
                    ],
                    "protocol_distribution": {
                        "labels": list(traffic_data["protocols"].keys()),
                        "data": list(traffic_data["protocols"].values()),
                        "colors": ["#ef4444", "#3b82f6", "#10b981", "#f59e0b", "#8b5cf6"]
                    },
                    "source_countries": {
                        "labels": list(traffic_data["source_distribution"].keys()),
                        "data": list(traffic_data["source_distribution"].values()),
                        "colors": ["#ef4444", "#3b82f6", "#10b981", "#f59e0b", "#8b5cf6", 
                                  "#ec4899", "#14b8a6", "#f97316", "#8b5cf6", "#06b6d4"]
                    },
                    "traffic_composition": {
                        "labels": ["Legitimate Traffic", "Attack Traffic"],
                        "data": [traffic_data["traffic_composition"]["legitimate_traffic"], 
                                traffic_data["traffic_composition"]["attack_traffic"]],
                        "colors": ["#10b981", "#ef4444"]
                    }
                },
                "metadata": {
                    "simulator_version": "2.0",
                    "execution_time": datetime.now().isoformat(),
                    "is_real_attack": False,
                    "purpose": "cybersecurity_education",
                    "complexity_level": "advanced"
                }
            }
            
            print(f"\n{self.colors['GREEN']}{'='*70}{self.colors['RESET']}")
            print(f"{self.colors['BOLD']}{self.colors['GREEN']}[✓] DDoS SIMULATION COMPLETE{self.colors['RESET']}")
            print(f"{self.colors['GREEN']}[✓] Total Requests: {attack_data['total_requests']:,}{self.colors['RESET']}")
            print(f"{self.colors['GREEN']}[✓] Peak Rate: {attack_data['peak_request_rate']:,} req/sec{self.colors['RESET']}")
            print(f"{self.colors['GREEN']}[✓] Risk Level: {risk_data['risk_level']}{self.colors['RESET']}")
            print(f"{self.colors['GREEN']}{'='*70}{self.colors['RESET']}")
            
            return json.dumps(results, indent=2)
            
        except Exception as e:
            print(f"{self.colors['RED']}[!] Simulation error: {e}{self.colors['RESET']}")
            
            # Fallback results
            error_results = {
                "error": str(e),
                "status": "failed",
                "simulation_type": "DDOS",
                "fallback_results": {
                    "total_requests": random.randint(1000, 10000),
                    "peak_request_rate": random.randint(100, 500),
                    "risk_level": "Medium",
                    "duration": 10
                }
            }
            return json.dumps(error_results)


def main():
    """Entry point for standalone execution"""
    # Parse command line arguments
    mode = "Medium"
    target = "Website"
    
    if len(sys.argv) > 1:
        mode = sys.argv[1]
    if len(sys.argv) > 2:
        target = sys.argv[2]
    
    simulator = AdvancedDDoSSimulator(mode=mode, target=target)
    results = simulator.run_simulation()
    print("\n" + results)


if __name__ == "__main__":
    main()
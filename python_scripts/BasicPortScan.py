import nmap
import socket
import ipaddress

def get_local_subnet():
    hostname = socket.gethostname()
    local_ip = socket.gethostbyname(hostname)
    # Default home/office networks use /24 usually
    network = ipaddress.ip_network(local_ip + "/24", strict=False)
    return str(network)

def discover_hosts(network):
    print(f"\n🔍 Discovering live hosts in {network} ...\n")
    nm = nmap.PortScanner()
    nm.scan(hosts=network, arguments='-sn')  # Ping/ARP scan

    live_hosts = []
    for host in nm.all_hosts():
        if nm[host].state() == "up":
            live_hosts.append(host)

    print(f"✨ Found {len(live_hosts)} live hosts:")
    for h in live_hosts:
        print(" -", h)

    return live_hosts

def basic_port_scan(host):
    print(f"\n🔎 Scanning all ports on {host} ...\n")
    nm = nmap.PortScanner()
    nm.scan(hosts=host, arguments='-sV -p-')

    if host not in nm.all_hosts():
        print(f"❌ {host} unreachable.")
        return

    host_info = nm[host]
    print(f"Host: {host} | State: {host_info.state()}")

    if 'tcp' in host_info:
        for port in sorted(host_info['tcp']):
            port_data = host_info['tcp'][port]
            print(
                f" {port}/tcp: {port_data['state']} | "
                f"{port_data['name']} | "
                f"{port_data.get('version', 'Unknown')}"
            )
    else:
        print("No open ports found.")

if __name__ == "__main__":
    subnet = get_local_subnet()
    hosts = discover_hosts(subnet)

    print("\n🚀 Starting full port scans...\n")
    for h in hosts:
        basic_port_scan(h)

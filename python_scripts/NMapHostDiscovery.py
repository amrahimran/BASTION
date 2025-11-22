import nmap
import socket
import json

def get_local_subnet():
    hostname = socket.gethostname()
    local_ip = socket.gethostbyname(hostname)
    return local_ip.rsplit('.', 1)[0] + ".0/24"

def scan_network():
    nm = nmap.PortScanner()
    subnet = get_local_subnet()

    nm.scan(hosts=subnet, arguments='-sn')

    hosts = []

    for host in nm.all_hosts():
        if nm[host].state() == "up":
            mac = nm[host]["addresses"].get("mac", "Unknown")
            vendor = nm[host]["vendor"].get(mac, "Unknown")
            hosts.append({
                "ip": host,
                "mac": mac,
                "vendor": vendor
            })

    return hosts

if __name__ == "__main__":
    results = scan_network()
    print(json.dumps({"hosts": results}))

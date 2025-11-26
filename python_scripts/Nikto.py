import subprocess
import socket
import ipaddress
import json

def get_local_ip():
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    try:
        s.connect(("8.8.8.8", 80))
        return s.getsockname()[0]
    except:
        return None
    finally:
        s.close()

def is_port_open(ip, port):
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.settimeout(0.3)
    try:
        return s.connect_ex((ip, port)) == 0
    except:
        return False
    finally:
        s.close()

def run_nikto_scan(ip):
    try:
        result = subprocess.run(
            ["nikto", "-h", ip],
            capture_output=True,
            text=True
        )
        return result.stdout
    except FileNotFoundError:
        return "Nikto not installed"
    except Exception as e:
        return f"Error: {str(e)}"

def main():
    local_ip = get_local_ip()
    if not local_ip:
        print(json.dumps({"nikto": [], "error": "Could not detect local IP"}))
        return

    # Use /24 subnet
    network = ipaddress.ip_network(local_ip + "/24", strict=False)

    web_hosts = []

    for ip in network.hosts():
        ip_str = str(ip)
        if is_port_open(ip_str, 80) or is_port_open(ip_str, 443):
            web_hosts.append(ip_str)

    nikto_results = []

    for host in web_hosts:
        scan_output = run_nikto_scan(host)
        nikto_results.append({
            "host": host,
            "output": scan_output
        })

    print(json.dumps({"nikto": nikto_results}))

if __name__ == "__main__":
    main()

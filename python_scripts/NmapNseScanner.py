import subprocess
import ipaddress
import socket
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

def generate_subnet(ip):
    net = ipaddress.IPv4Network(ip + "/24", strict=False)
    return [str(host) for host in net.hosts()]

def run_nmap(target):
    cmd = [
        "nmap",
        "-sV",
        "--script=vuln,smb-vuln*,http-vuln*,ftp-anon",
        "-T4",
        target
    ]

    try:
        result = subprocess.run(cmd, capture_output=True, text=True)
        return {
            "host": target,
            "output": result.stdout,
            "error": result.stderr
        }
    except Exception as e:
        return {
            "host": target,
            "output": "",
            "error": str(e)
        }

def main():
    data = {
        "local_ip": None,
        "subnet": None,
        "results": []
    }

    ip = get_local_ip()
    if not ip:
        print(json.dumps({"error": "Could not detect IP"}))
        return

    data["local_ip"] = ip
    data["subnet"] = ".".join(ip.split(".")[:3]) + ".0/24"

    hosts = generate_subnet(ip)

    for host in hosts:
        data["results"].append(run_nmap(host))

    print(json.dumps(data))

if __name__ == "__main__":
    main()

import socket
from ftplib import FTP, error_perm
import sys
sys.stdout.reconfigure(encoding='utf-8')


def get_local_ip():
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    s.connect(("8.8.8.8", 80))
    ip = s.getsockname()[0]
    s.close()
    return ip


def generate_subnet(ip):
    base = ".".join(ip.split(".")[:3])
    return [f"{base}.{i}" for i in range(1, 255)]


def check_ftp(ip):
    # If you hit CTRL+C inside this, Python exits instantly
    try:
        ftp = FTP(ip, timeout=1)
        ftp.login("anonymous", "anonymous@domain.com")
        print(f"[+] Anonymous login ALLOWED on {ip}")
        ftp.quit()

    except error_perm:
        print(f"[-] Anonymous denied on {ip}")

    except Exception:
        # Ignore unreachable hosts quietly
        pass


def main():
    print(" Detecting your IP...")
    ip = get_local_ip()
    print(f" Local IP: {ip}")

    print("\n Generating /24 subnet...")
    hosts = generate_subnet(ip)

    print(f" Total hosts: {len(hosts)}\n")

    for host in hosts:
        print(f"Checking {host} ...", end="\r")
        check_ftp(host)

    print("\n Scan complete.")


if __name__ == "__main__":
    main()

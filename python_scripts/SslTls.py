import subprocess
import socket
import ipaddress
import signal
import sys
import json

stop_flag = False
DEBUG = not hasattr(sys, 'argv') or len(sys.argv) == 1  # Enable prints only when user runs manually


def debug_print(msg):
    if DEBUG:
        print(msg)


def sigint_handler(sig, frame):
    global stop_flag
    stop_flag = True
    debug_print("\n[!] Stopping scan...")


signal.signal(signal.SIGINT, sigint_handler)


def get_local_ip():
    try:
        s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
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


def run_sslscan(target):
    debug_print(f"\n Running SSLScan on {target}...\n")
    result_output = ""

    # sslscan_cmd = "sslscan.exe" if sys.platform == "win32" else "sslscan"
    sslscan_cmd = r"C:\Users\VICTUS\Downloads\sslscan-2.2.0\sslscan.exe"


    try:
        process = subprocess.Popen(
            [sslscan_cmd, target],
            stdout=subprocess.PIPE,
            stderr=subprocess.STDOUT,
            text=True
        )

        for line in process.stdout:
            if stop_flag:
                process.terminate()
                break
            debug_print(line.rstrip())
            result_output += line

        process.wait()

    except FileNotFoundError:
        return "[ERROR] sslscan not installed.\n"

    return result_output


def main():
    local_ip = get_local_ip()
    if not local_ip:
        print(json.dumps({"error": "Could not detect local IP"}))
        sys.exit(1)

    debug_print(f"[+] Local IP detected: {local_ip}")

    network = ipaddress.ip_network(f"{local_ip}/24", strict=False)
    debug_print(f"[+] Scanning subnet {network} for HTTPS servers...\n")

    https_hosts = []

    for host in network.hosts():
        if stop_flag:
            break

        ip_str = str(host)
        debug_print(f"[*] Checking port 443 on {ip_str} ...")

        if is_port_open(ip_str, 443):
            debug_print(f"[+] HTTPS found: {ip_str}")
            https_hosts.append(ip_str)

    if not https_hosts:
        output = {"sslscan": [], "message": "No HTTPS hosts found"}
        print(json.dumps(output))
        return

    debug_print(f"\n[+] Found {len(https_hosts)} HTTPS targets. Running SSLScan...\n")

    final_results = {}

    for target in https_hosts:
        if stop_flag:
            break
        final_results[target] = run_sslscan(target)

    print(json.dumps({"sslscan": final_results}))
    

if __name__ == "__main__":
    main()

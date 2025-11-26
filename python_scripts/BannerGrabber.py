import socket
import threading
import queue
import json

PORTS = [21, 22, 23, 25, 53, 80, 110, 139, 143, 443, 445, 3306, 8080]
SOCKET_TIMEOUT = 0.8
THREAD_COUNT = 50

results = []
results_lock = threading.Lock()

def scan_port(ip, port):
    try:
        s = socket.socket()
        s.settimeout(SOCKET_TIMEOUT)
        s.connect((ip, port))

        try:
            banner = s.recv(1024).decode(errors="ignore").strip()
        except:
            banner = ""

        s.close()

        with results_lock:
            results.append({
                "ip": ip,
                "port": port,
                "banner": banner if banner else None,
                "status": "open"
            })

    except:
        pass


def scan_host(ip):
    for port in PORTS:
        scan_port(ip, port)


def worker(q):
    while True:
        try:
            ip = q.get_nowait()
        except queue.Empty:
            return
        scan_host(ip)
        q.task_done()


def get_local_ip_base():
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    try:
        s.connect(("8.8.8.8", 80))
        ip = s.getsockname()[0]
    except:
        ip = "192.168.1.1"
    finally:
        s.close()

    return ".".join(ip.split(".")[:3]) + "."


def main():
    base = get_local_ip_base()
    ips = [f"{base}{i}" for i in range(1, 255)]

    q = queue.Queue()
    for ip in ips:
        q.put(ip)

    threads = []
    for _ in range(THREAD_COUNT):
        t = threading.Thread(target=worker, args=(q,))
        t.start()
        threads.append(t)

    for t in threads:
        t.join()

    print(json.dumps({
        "subnet": base + "0/24",
        "results": results
    }))


if __name__ == "__main__":
    main()

import socket
import paramiko
import json

def check_ssh(host, port=22, timeout=3):
    result = {
        "host": host,
        "port": port,
        "status": "unknown",
        "kex": [],
        "ciphers": [],
        "macs": [],
        "weak": False,
        "error": None
    }

    try:
        sock = socket.create_connection((host, port), timeout=timeout)
        transport = paramiko.Transport(sock)
        transport.start_client(timeout=timeout)

        sec_opts = transport.get_security_options()
        result["kex"] = sec_opts.kex
        result["ciphers"] = sec_opts.ciphers
        result["macs"] = sec_opts.macs

        # Weak checks
        weak_kex = any(w in sec_opts.kex for w in ["diffie-hellman-group1-sha1"])
        weak_cipher = any(w in sec_opts.ciphers for w in ["aes128-cbc", "3des-cbc"])
        weak_mac = any(w in sec_opts.macs for w in ["hmac-md5"])

        result["weak"] = weak_kex or weak_cipher or weak_mac
        result["status"] = "success"

        transport.close()
    except Exception as e:
        result["status"] = "error"
        result["error"] = str(e)

    return result

# Example usage
hosts = ["192.168.56.1", "192.168.56.2"]
results = []

for host in hosts:
    results.append(check_ssh(host))

# Output valid JSON
print(json.dumps(results))

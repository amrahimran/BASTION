import nmap
import sys
import json

def deep_scan(host):
    nm = nmap.PortScanner()
    nm.scan(hosts=host, arguments='-sV -p-')

    results = []

    if host in nm.all_hosts() and 'tcp' in nm[host]:
        for port in sorted(nm[host]['tcp']):
            pd = nm[host]['tcp'][port]
            results.append({
                "port": port,
                "service": pd.get("name", "unknown"),
                "version": pd.get("version", "unknown"),
                "state": pd.get("state", "unknown")
            })

    return results

if __name__ == "__main__":
    host = sys.argv[1]
    scan_results = deep_scan(host)
    print(json.dumps({"ports": scan_results}))

import dns.resolver
import dns.query
import dns.message
import json

def check_open_resolver(target_dns):
    result = {"dns_server": target_dns}

    try:
        query = dns.message.make_query("example.com", dns.rdatatype.A)
        response = dns.query.udp(query, target_dns, timeout=3)

        # Recursion Allowed (open resolver)
        result["open_resolver"] = bool(response.flags & dns.flags.RA)

    except Exception as e:
        result["error"] = str(e)

    return result


def check_dns_spoofing(target_dns):
    result = {"dns_server": target_dns}

    resolver = dns.resolver.Resolver()
    resolver.nameservers = [target_dns]

    try:
        answers = resolver.resolve("example.com", "A")
        ips = [r.address for r in answers]
        result["resolved_ips"] = ips

        legit_ips = {"93.184.216.34"}  # Official example.com IP
        result["spoofing_detected"] = not bool(set(ips).intersection(legit_ips))

    except Exception as e:
        result["error"] = str(e)

    return result


def main():
    targets = [
        "8.8.8.8",
        "1.1.1.1",
        "9.9.9.9"
    ]

    final_output = {"dns": []}

    for target in targets:
        dns_result = {
            "dns_server": target,
            "open_resolver": check_open_resolver(target).get("open_resolver"),
            "spoofing": check_dns_spoofing(target),
        }
        final_output["dns"].append(dns_result)

    # Output JSON for Laravel
    print(json.dumps(final_output))


if __name__ == "__main__":
    main()

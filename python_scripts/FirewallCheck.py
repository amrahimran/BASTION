import subprocess
import json

def run_command(cmd):
    """Run a shell command and return output as text."""
    try:
        result = subprocess.check_output(cmd, shell=True, text=True, stderr=subprocess.STDOUT)
        return result.strip()
    except subprocess.CalledProcessError as e:
        return f"Error running command: {e.output}"

def parse_firewall_output(raw):
    """Parse the netsh firewall output into structured sections."""
    profiles = {}
    current_profile = None

    for line in raw.splitlines():
        line = line.strip()

        # Detect profile name (Domain, Private, Public)
        if line.endswith("Profile Settings:"):
            current_profile = line.replace("Profile Settings:", "").strip()
            profiles[current_profile] = {}
            continue

        # Parse Key : Value lines
        if ":" in line and current_profile:
            key, value = line.split(":", 1)
            profiles[current_profile][key.strip()] = value.strip()

    return profiles


def check_windows_firewall():
    raw_output = run_command("netsh advfirewall show allprofiles")

    if "Error" in raw_output:
        return {"firewall": {"error": raw_output}}

    profiles = parse_firewall_output(raw_output)

    return {
        "firewall": {
            "profiles": profiles,
            "raw_output": raw_output
        }
    }


if __name__ == "__main__":
    result = check_windows_firewall()
    print(json.dumps(result))

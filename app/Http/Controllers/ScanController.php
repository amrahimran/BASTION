<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function runScan(Request $request)
    {
        ini_set('max_execution_time', 0);

        $request->validate([
            'target' => 'required_without:auto_detect|string|nullable',
        ]);

        $autoDetect = $request->boolean('auto_detect');
        $scanMode = $request->input('scan_mode', 'fast');

        /*
        |--------------------------------------------------------------------------
        | AUTO-DETECT LOGIC
        |--------------------------------------------------------------------------
        | If user selects Auto Detect LAN, dynamically detect local subnet
        | and scan all devices in it.
        */

        if ($autoDetect) {
            // 1. Detect local IP dynamically
            $localIp = trim(shell_exec("ipconfig | findstr /R /C:\"IPv4\"")) ?: '192.168.1.10';
            if (preg_match('/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/', $localIp, $matches)) {
                $localIp = $matches[1];

                // Assume /24 subnet if real mask detection fails
                $subnet = preg_replace('/\d+$/', '0', $localIp) . '/24';
            } else {
                $subnet = '192.168.1.0/24';
            }

            // 2. Nmap ping scan to detect live hosts
            $discoverCmd = '"C:\\Program Files (x86)\\Nmap\\nmap.exe" -sn ' . escapeshellcmd($subnet);
            $discoverOutput = shell_exec($discoverCmd);

            // 3. Extract live hosts
            preg_match_all('/Nmap scan report for ([0-9.]+)/', $discoverOutput, $detected);
            $targets = $detected[1] ?? [];

            // Fallback if no hosts detected
            if (empty($targets)) {
                $targets = [$subnet];
            }

        } else {
            $targets = [$request->input('target')];
        }

        /*
        |--------------------------------------------------------------------------
        | MAIN PORT SCAN LOGIC
        |--------------------------------------------------------------------------
        */

        $ports = [];
        $rawOutputs = [];

        foreach ($targets as $target) {

            $options = ($scanMode === 'fast|| $autoDetect') 
                ? '-F'                  // fast scan
                : '-p- -sV -A';         // deep scan

            $command = '"C:\\Program Files (x86)\\Nmap\\nmap.exe" ' . $options . ' ' . escapeshellcmd($target);

            $output = shell_exec($command);
            $rawOutputs[$target] = $output;

            if (!$output) {
                $rawOutputs[$target] = "Nmap could not run for {$target}. Make sure it is installed.";
                continue;
            }

            $lines = explode("\n", $output);

            foreach ($lines as $line) {
                if (preg_match('/^(\d+)\/tcp\s+open\s+(\S+)/', trim($line), $matches)) {

                    $port = intval($matches[1]);
                    $service = $matches[2];

                    // Get risk level + reason
                    [$risk, $reason] = $this->getPortRisk($port);

                    $ports[] = [
                        "target"  => $target,
                        "port"    => $port,
                        "service" => $service,
                        "risk"    => $risk,
                        "reason"  => $reason
                    ];
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RISK COUNTS
        |--------------------------------------------------------------------------
        */

        $low = collect($ports)->where('risk', 'Low')->count();
        $medium = collect($ports)->where('risk', 'Medium')->count();
        $high = collect($ports)->where('risk', 'High')->count();

        /*
        |--------------------------------------------------------------------------
        | RETURN TO VIEW
        |--------------------------------------------------------------------------
        */

        return view('profile.scanresult', [
            'targets' => $targets,
            'ports'   => $ports,
            'low'     => $low,
            'medium'  => $medium,
            'high'    => $high,
            'scanMode' => $scanMode,
            'autoDetect' => $autoDetect,
            'rawOutput' => $rawOutputs,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER: PORT RISK LOGIC
    |--------------------------------------------------------------------------
    */

    private function getPortRisk($port)
    {
        $risk   = "Low";
        $reason = "This service is generally safe and rarely targeted.";

        switch ($port) {

            // WEB PORTS
            case 80:
            case 443:
                return ["Medium", "Web server ports: commonly targeted for SQL injection, XSS, and brute force attacks."];

            // SSH / FTP
            case 22:
                return ["Medium", "SSH remote access: secure, but commonly brute‑forced when exposed publicly."];
            case 21:
                return ["Medium", "FTP: uses plain text logins; attackers can sniff or brute‑force credentials."];

            // DATABASES
            case 3306:
                return ["High", "MySQL exposed: attackers may attempt database intrusion or data theft."];
            case 5432:
                return ["High", "PostgreSQL exposed: unauthorized access attempts possible."];

            // WINDOWS SERVICES
            case 135:
            case 139:
            case 445:
                return ["High", "Windows SMB / RPC ports: widely exploited by ransomware (e.g., WannaCry)."];

            // RDP
            case 3389:
                return ["High", "Remote Desktop exposed: vulnerable to brute‑force attacks and exploits."];

            // DEV PORTS
            case 8000:
            case 8080:
            case 5000:
                return ["Medium", "Development server port: should not be exposed in production."];
        }

        return [$risk, $reason];
    }
}

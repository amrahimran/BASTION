<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function showScanPage()
    {
        return view('scan');
    }

    public function runScan(Request $request)
    {
        ini_set('max_execution_time', 0);
        $request->validate([
            'target' => 'nullable|string',
            'scan_mode' => 'required|string',
        ]);

        $autoDetect = $request->has('auto_detect');
        $scanMode = $request->input('scan_mode');
        $targets = [];

        // ==============================
        // 1. BUILD TARGET LIST
        // ==============================
        if ($autoDetect) {
            $localIp = getHostByName(getHostName());
            $subnet = implode('.', array_slice(explode('.', $localIp), 0, 3)) . ".0/24";
            $pingCmd = "\"C:\\Program Files (x86)\\Nmap\\nmap.exe\" -sn $subnet";
            $pingOutput = shell_exec($pingCmd);
            preg_match_all('/Nmap scan report for ([0-9.]+)/', $pingOutput, $aliveHosts);
            $targets = $aliveHosts[1] ?? [];
            if (empty($targets)) {
                $targets = [$localIp];
            }
        } else {
            $targets[] = $request->input('target');
        }

        // ==============================
        // 2. SCAN OPTIONS
        // ==============================
        $options = $scanMode === 'fast' ? "-F -Pn" : "-p- -sV -A -Pn";
        $nmap = "\"C:\\Program Files (x86)\\Nmap\\nmap.exe\"";

        $parsedPorts = [];
        $rawOutput = [];

        // ==============================
        // 3. DEFINE RISK EXPLANATIONS
        // ==============================
        $riskMessages = [
            'High' => [
                'ports' => [
                    21 => 'FTP (21) can expose credentials in plain text.',
                    23 => 'Telnet (23) is unencrypted and easily exploited.',
                    445 => 'SMB (445) is often targeted for ransomware attacks.',
                    3306 => 'MySQL (3306) exposes databases if misconfigured.'
                ],
                'features' => [],
                'default' => 'This service is critical and highly vulnerable.'
            ],
            'Medium' => [
                'ports' => [
                    80 => 'HTTP (80) may leak server info if misconfigured.',
                    443 => 'HTTPS (443) misconfigurations can expose sensitive info.',
                    135 => 'RPC (135) can be exploited for internal attacks.'
                ],
                'features' => [
                    'ssh_weak_config' => 'SSH weak config may allow password brute-force attacks.',
                    'ftp_anonymous' => 'Anonymous FTP allows anyone to read/write files.',
                    'http_headers' => 'HTTP headers may reveal server or software info.',
                    'snmp_scan' => 'SNMP info may disclose network devices and configs.',
                    'docker' => 'Exposed Docker service may allow container hijacking.',
                    'firewall' => 'Firewall misconfigurations may allow access to restricted ports.',
                    'passive_sniff' => 'Network sniffing may expose sensitive traffic.',
                    'dns_misconfig' => 'DNS misconfigurations may leak internal network info.'
                ],
                'default' => 'This service may expose information depending on configuration.'
            ],
            'Low' => [
                'ports' => [],
                'features' => [],
                'default' => 'This service is generally safe but should be monitored.'
            ]
        ];

        // ==============================
        // 4. SCAN EACH TARGET
        // ==============================
        foreach ($targets as $target) {
            $command = "$nmap $options " . escapeshellcmd($target);
            $output = shell_exec($command);

            if (!$output) {
                $rawOutput[$target] = "Host offline or blocked.";
                continue;
            }

            $rawOutput[$target] = $output;

            // Parse open ports
            preg_match_all('/(\d+)\/tcp\s+open\s+([a-zA-Z0-9\-]+)/', $output, $matches, PREG_SET_ORDER);
            foreach ($matches as $m) {
                $port = (int)$m[1];
                $service = $m[2];

                if (isset($riskMessages['High']['ports'][$port])) {
                    $risk = 'High';
                    $reason = $riskMessages['High']['ports'][$port];
                } elseif (isset($riskMessages['Medium']['ports'][$port])) {
                    $risk = 'Medium';
                    $reason = $riskMessages['Medium']['ports'][$port];
                } else {
                    $risk = 'Low';
                    $reason = $riskMessages['Low']['default'];
                }

                $parsedPorts[] = [
                    'target' => $target,
                    'port' => $port,
                    'service' => $service,
                    'risk' => $risk,
                    'reason' => $reason
                ];
            }

            // Feature-based checks
            foreach ($request->input('features', []) as $feature) {
                if (isset($riskMessages['High']['features'][$feature])) {
                    $parsedPorts[] = [
                        'target' => $target,
                        'port' => null,
                        'service' => $feature,
                        'risk' => 'High',
                        'reason' => $riskMessages['High']['features'][$feature]
                    ];
                } elseif (isset($riskMessages['Medium']['features'][$feature])) {
                    $parsedPorts[] = [
                        'target' => $target,
                        'port' => null,
                        'service' => $feature,
                        'risk' => 'Medium',
                        'reason' => $riskMessages['Medium']['features'][$feature]
                    ];
                } else {
                    $parsedPorts[] = [
                        'target' => $target,
                        'port' => null,
                        'service' => $feature,
                        'risk' => 'Low',
                        'reason' => $riskMessages['Low']['default']
                    ];
                }
            }
        }

        // ==============================
        // 5. BUILD RESULTS ARRAY FOR BLADE
        // ==============================
        $results = [];
        foreach ($targets as $target) {
            $portsForTarget = array_filter($parsedPorts, fn($p) => $p['target'] === $target);

            $results[$target] = [
                'ports' => $portsForTarget,
                'raw' => $rawOutput[$target] ?? "No raw output available."
            ];
        }

        return view('profile.scanresult', [
            'scanMode' => $scanMode,
            'autoDetect' => $autoDetect,
            'targets' => $targets,
            'ports' => $parsedPorts,
            'rawOutput' => $rawOutput,
            'results' => $results
        ]);
    }
}

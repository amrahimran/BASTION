<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scan;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    public function showScanPage()
    {
        return view('profile.scan');
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

        // 1. TARGET DETECTION
        if ($autoDetect) {
            $localIp = getHostByName(getHostName());
            $subnet = implode('.', array_slice(explode('.', $localIp), 0, 3)) . ".0/24";

            $pingCmd = "\"C:\\Program Files (x86)\\Nmap\\nmap.exe\" -sn $subnet";
            $pingOutput = shell_exec($pingCmd);

            preg_match_all('/Nmap scan report for ([0-9.]+)/', $pingOutput, $alive);
            $targets = $alive[1] ?? [];

            if (empty($targets)) {
                $targets = [$localIp];
            }
        } else {
            $targets[] = $request->input('target');
        }

        // 2. SCAN OPTIONS
        $options = $scanMode === 'fast' ? "-F -Pn" : "-p- -sV -A -Pn";
        $nmap = "\"C:\\Program Files (x86)\\Nmap\\nmap.exe\"";

        $results = [];

        // 3. RISK DEFINITIONS
        $riskMessages = [
            'High' => [
                'ports' => [
                    21 => 'FTP (21) exposes plain-text credentials.',
                    23 => 'Telnet (23) is unencrypted and unsafe.',
                    445 => 'SMB (445) is a known ransomware target.',
                    3306 => 'MySQL (3306) exposes databases.',
                ],
                'default' => 'This service is highly vulnerable.'
            ],
            'Medium' => [
                'ports' => [
                    80 => 'HTTP (80) may leak server info.',
                    443 => 'HTTPS (443) misconfigurations can expose data.',
                    135 => 'RPC (135) can be abused internally.',
                ],
                'default' => 'Service may expose information depending on config.'
            ],
            'Low' => [
                'default' => 'Generally safe but still monitor regularly.'
            ]
        ];

        // 4. SCAN LOOP
        foreach ($targets as $target) {
            $parsedPortsForThisTarget = [];

            $cmd = "$nmap $options " . escapeshellcmd($target);
            $output = shell_exec($cmd);

            if (!$output) {
                $results[$target] = [
                    'ports' => [],
                    'raw' => "Host offline or blocked."
                ];
                continue;
            }

            // PARSE PORTS
            preg_match_all('/(\d+)\/tcp\s+open\s+([a-zA-Z0-9\-]+)/', $output, $matches, PREG_SET_ORDER);

            foreach ($matches as $m) {
                $port = intval($m[1]);
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

                $parsedPortsForThisTarget[] = [
                    'port' => $port,
                    'service' => $service,
                    'risk' => $risk,
                    'reason' => $reason
                ];
            }

            // SAVE TO DATABASE
            Scan::create([
                'user_id' => Auth::id(),
                'target' => $target,
                'scan_mode' => $scanMode,
                'auto_detect' => $autoDetect,
                'features' => $request->input('features', []),
                'ports' => $parsedPortsForThisTarget, // ✅ store parsed table
                'raw_output' => $output,
            ]);

            // ADD TO RESULTS ARRAY
            $results[$target] = [
                'ports' => $parsedPortsForThisTarget,
                'raw' => $output
            ];
        }

        return view('profile.scanresult', [
            'scanMode' => $scanMode,
            'autoDetect' => $autoDetect,
            'targets' => $targets,
            'results' => $results,
        ]);
    }

    // 5. REPORTS PAGE
    public function reports()
    {
        $scans = Scan::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.scanreports', compact('scans'));
    }

    // 6. EXPORT CSV
        public function exportSingleCsv(Scan $scan)
        {
            $filename = "scan_report_{$scan->id}_" . now()->format('Ymd_His') . ".csv";
            $headers = [
                "Content-Type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename"
            ];

            $callback = function() use($scan) {
                $file = fopen('php://output', 'w');

                fputcsv($file, ['Port','Service','Risk','Reason']);
                $ports = $scan->ports ?? [];
                foreach ($ports as $p) {
                    fputcsv($file, [
                        $p['port'] ?? '',
                        $p['service'] ?? '',
                        $p['risk'] ?? '',
                        $p['reason'] ?? '',
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

}

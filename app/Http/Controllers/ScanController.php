<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Scan;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class ScanController extends Controller
{
    public function showScanPage()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        // Full list of scan features
        $featuresList = [
            'nmap_host_discovery' => [
                'label' => 'Nmap Host Discovery',
                'desc'  => 'Detects which devices are alive on the network.'
            ],
            'nmap_basic_port_scan' => [
                'label' => 'Basic Port Scan',
                'desc'  => 'Scans common open ports that attackers target.'
            ],
            'os_fingerprinting' => [
                'label' => 'OS Fingerprinting',
                'desc'  => 'Tries to identify the operating system version.'
            ],
            'banner_grabbing' => [
                'label' => 'Banner Grabbing',
                'desc'  => 'Collects service banners that may reveal versions.'
            ],
            'ssh_weak_config' => [
                'label' => 'Weak SSH Configuration Check',
                'desc'  => 'Detects weak algorithms or outdated SSH settings.'
            ],
            'ftp_anonymous' => [
                'label' => 'FTP Anonymous Login',
                'desc'  => 'Checks if FTP allows login without credentials.'
            ],
            'smb_share_scan' => [
                'label' => 'SMB Share Scan',
                'desc'  => 'Finds open Windows shares that expose files.'
            ],
            'http_headers' => [
                'label' => 'HTTP Security Headers',
                'desc'  => 'Checks if a website is missing security headers.'
            ],
            'snmp_scan' => [
                'label' => 'SNMP v1/v2 Public Scan',
                'desc'  => 'Searches for devices with default public SNMP.'
            ],
            'nmap_nse' => [
                'label' => 'Nmap NSE Scripts',
                'desc'  => 'Runs vulnerability scripts for deeper checks.'
            ],
            'nikto' => [
                'label' => 'Nikto Web Scan (Simulated)',
                'desc'  => 'Detects common web server misconfigurations.'
            ],
            'ssl_tls' => [
                'label' => 'SSL/TLS Scan',
                'desc'  => 'Checks SSL versions, ciphers, and weaknesses.'
            ],
            'os_patch' => [
                'label' => 'OS Patch Check',
                'desc'  => 'Simulates outdated OS or missing patch issues.'
            ],
            'docker' => [
                'label' => 'Docker Misconfigurations',
                'desc'  => 'Checks for exposed Docker sockets/weak setups.'
            ],
            'firewall' => [
                'label' => 'Firewall Status',
                'desc'  => 'Tests whether firewall rules are active.'
            ],
            'passive_sniff' => [
                'label' => 'Passive Network Sniffing',
                'desc'  => 'Detects unencrypted or exposed network traffic.'
            ],
            'dns_misconfig' => [
                'label' => 'DNS Misconfiguration Check',
                'desc'  => 'Finds common DNS issues like open resolvers.'
            ],
        ];

        return view('profile.scan', compact('featuresList'));
    }


    public function runScan(Request $request)
    {
        set_time_limit(300); // 5 minutes

        // Validation: Auto detection MUST be enabled
        if (!$request->has('auto_detect') || !$request->boolean('auto_detect')) {
            return redirect()
                ->back()
                ->with('error', 'Auto Detect LAN Devices must be enabled before running a scan.');
        }

        $user = Auth::user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $autoDetect = true;
        $scanMode = $request->scan_mode ?? 'fast';
        $features = $request->features ?? [];

        $parsedResults = [];
        $rawOutput = "";

        // Target host (can be made dynamic via request)
        $host = "127.0.0.1";

        // Helper function to run Python script
        $runPythonScript = function($scriptPath) use ($host) {
            $cmd = "python \"$scriptPath\" $host 2>&1";
            $output = shell_exec($cmd);

            if (!$output) {
                return [[], "No output from script"];
            }

            $decoded = json_decode($output, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [[], "Invalid JSON output:\n$output"];
            }

            return [$decoded, $output];
        };

        // Run host discovery
        if (in_array('nmap_host_discovery', $features)) {
            $script = base_path("python_scripts/NMapHostDiscovery.py");
            [$result, $output] = $runPythonScript($script);
            $rawOutput .= "\n\n=== Host Discovery ===\n" . $output;
            $parsedResults['hosts'] = $result['hosts'] ?? [];
        }

        // Run port scan
        if (in_array('nmap_basic_port_scan', $features)) {
            $script = $scanMode === 'fast'
                ? base_path("python_scripts/basic_port_fast.py")
                : base_path("python_scripts/basic_port_deep.py");

            [$result, $output] = $runPythonScript($script);
            $rawOutput .= "\n\n=== Port Scan ({$scanMode}) ===\n" . $output;
            $parsedResults['ports'] = $result['ports'] ?? [];
        }

        // Save scan to database
        $scan = Scan::create([
            'user_id' => $user->id,
            'target' => $host,
            'scan_mode' => $scanMode,
            'auto_detect' => $autoDetect,
            'features' => $features,
            'parsed_results' => $parsedResults,
            'raw_output' => $rawOutput,
        ]);

        //return redirect()->route('scan.reports')->with('success', "Scan #{$scan->id} completed!");
        return redirect()->route('scan.result', $scan->id)->with('success', "Scan #{$scan->id} completed!");

    }

    public function showScanResult($id)
    {
        $scan = Scan::findOrFail($id);
        $scan->parsed_results = json_decode($scan->parsed_results, true) ?? [];

        // Add detailed parsed results with risk and descriptions
        $scan->parsed_results_detailed = $this->parseScanResults($scan);

        return view('profile.scanresult', compact('scan'));
    }

    public function parseScanResults($scan)
{
    $results = [];

    // Hosts
    if(!empty($scan->parsed_results['hosts'])){
        foreach($scan->parsed_results['hosts'] as $host){
            $results['hosts'][] = [
                'ip' => $host['ip'] ?? '-',
                'mac' => $host['mac'] ?? '-',
                'vendor' => $host['vendor'] ?? '-',
                'risk_level' => 'Low', // Usually discovery is low risk
                'description' => 'Device found on network. Could be your computer, router, or IoT device.'
            ];
        }
    }

    // Ports
    if(!empty($scan->parsed_results['ports'])){
        foreach($scan->parsed_results['ports'] as $port){
            $risk = 'Low';
            $desc = 'Port is open, standard service running.';

            // Assign risk levels based on port/service
            if(in_array($port['port'], [21, 22, 23])){ // FTP, SSH, Telnet
                $risk = 'Medium';
                $desc = 'Open administrative port. Check if password-protected.';
            }
            if(in_array($port['port'], [3389, 5900])){ // RDP, VNC
                $risk = 'High';
                $desc = 'Remote access port open. Exposed to external attacks.';
            }

            $results['ports'][] = [
                'port' => $port['port'] ?? '-',
                'service' => $port['service'] ?? '-',
                'state' => $port['state'] ?? '-',
                'risk_level' => $risk,
                'description' => $desc
            ];
        }
    }

    return $results;
}
     public function reports()
    {
        $scans = Scan::orderBy('created_at', 'desc')->get();

        // Parse results for each scan before sending to view
        foreach ($scans as $scan) {
            // Assuming parsedScanResults returns ['hosts'=>[], 'ports'=>[]] with risk_level & description
            $scan->parsed_results_detailed = $this->parseScanResults($scan);
        }

        return view('profile.scanreports', compact('scans'));
    }

    public function exportSinglePdf($id)
    {
        $scan = Scan::findOrFail($id);

        $pdf = Pdf::loadView('reports.scan_single', compact('scan'))
                ->setPaper('A4', 'portrait');

        return $pdf->download("scan_report_{$scan->id}.pdf");
    }

    public function exportAllPdf()
    {
        $scans = Scan::orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('reports.all_scans', compact('scans'))
                ->setPaper('A4', 'landscape');

        return $pdf->download("all_scan_reports.pdf");
    }



}

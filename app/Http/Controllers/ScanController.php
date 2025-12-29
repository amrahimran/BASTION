<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Scan;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Http;
use App\Services\GeminiService;





class ScanController extends Controller
{
    protected $featuresList = [
        'nmap_host_discovery', 'nmap_basic_port_scan', 'os_fingerprinting',
        'banner_grabbing', 'ssh_weak_config', 'ftp_anonymous', 'smb_share_scan',
        'nmap_nse', 'nikto', 'ssl_tls', 'docker', 'firewall',
        'passive_sniffing', 'dns_misconfig'
    ];

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
            'short' => 'Finds which devices are currently active on the network.',
            'long'  => 'This scan checks your local network to discover which devices (phones, laptops, routers, IoT gadgets) are turned on and reachable. It\'s like scanning your Wi-Fi to see who is connected right now — helpful for spotting unfamiliar devices.'
        ],
        'nmap_basic_port_scan' => [
            'label' => 'Basic Port Scan',
            'short' => 'Looks for common “doors” services use to talk over the network.',
            'long'  => 'This scan inspects common network ports — think of them as doors apps use to communicate. It tells you which doors are open on a device so you can see if unnecessary services are exposed to others.'
        ],
        'os_fingerprinting' => [
            'label' => 'OS Fingerprinting',
            'short' => 'Guesses what operating system a device is running.',
            'long'  => 'This check attempts to identify the operating system (Windows, Linux, etc.) of devices it sees. It helps you understand what types of devices are on the network and whether they might need security updates.'
        ],
        'banner_grabbing' => [
            'label' => 'Banner Grabbing',
            'short' => 'Collects short service information that may show version numbers.',
            'long'  => 'When services communicate, they sometimes share a small message (“banner”) that reveals the software and version. This scan reads those messages to help identify outdated software that may need updating.'
        ],
        'ssh_weak_config' => [
            'label' => 'Weak SSH Configuration Check',
            'short' => 'Checks if SSH (secure login) is using weak settings.',
            'long'  => 'SSH is a secure remote-login tool. This check looks for weak or outdated settings that could let attackers guess or break into remote access more easily. If issues are found, consider hardening the SSH setup.'
        ],
        'ftp_anonymous' => [
            'label' => 'FTP Anonymous Login',
            'short' => 'Sees whether the FTP server allows anyone to log in without a password.',
            'long'  => 'Some FTP servers allow “anonymous” access so anyone can connect and upload/download files. This scan tests if that is allowed — which can be a data exposure risk if sensitive files are stored there.'
        ],
        'smb_share_scan' => [
            'label' => 'SMB Share Scan',
            'short' => 'Finds shared folders on Windows-style file shares.',
            'long'  => 'This scan looks for shared folders on systems (common for Windows networks). It helps discover if files or folders are accessible to others on the network without proper restrictions.'
        ],
        'nmap_nse' => [
            'label' => 'Nmap NSE Scripts',
            'short' => 'Runs a set of deeper checks to spot known issues.',
            'long'  => 'Nmap NSE is a collection of scripts that test for a range of vulnerabilities and configuration problems. This option runs some of those scripts to find known weaknesses more accurately.'
        ],
        'nikto' => [
            'label' => 'Nikto Web Scan (Simulated)',
            'short' => 'Checks web servers for common misconfigurations and issues.',
            'long'  => 'Nikto looks for frequent web server problems (like default files, outdated modules, or unsafe settings). It is a quick way to spot basic web server issues that could let attackers probe your site.'
        ],
        'ssl_tls' => [
            'label' => 'SSL/TLS Scan',
            'short' => 'Inspects the security of the site’s encryption settings (HTTPS).',
            'long'  => 'This scan checks whether the server uses modern and secure encryption for HTTPS connections. It will flag weak ciphers or outdated protocol versions that weaken secure connections.'
        ],
        'docker' => [
            'label' => 'Docker Misconfigurations',
            'short' => 'Searches for common insecure Docker or container setups.',
            'long'  => 'Containers (Docker) can be powerful but risky when configured wrongly — e.g., exposing admin sockets or leaving sensitive files open. This scan looks for those common misconfigurations.'
        ],
        'firewall' => [
            'label' => 'Firewall Status',
            'short' => 'Checks whether firewall rules are present and active.',
            'long'  => 'This check inspects whether a firewall appears to be running and blocking outside access. A missing or misconfigured firewall can leave systems unnecessarily exposed to the internet or local network.'
        ],
        'passive_sniffing' => [
            'label' => 'Passive Network Sniffing',
            'short' => 'Briefly listens for unencrypted network traffic (read-only).',
            'long'  => 'This mode listens to nearby network traffic to detect unencrypted data (like plain text passwords or other sensitive info). It does not modify traffic — it just looks — and helps identify risky unencrypted communication.'
        ],
        'dns_misconfig' => [
            'label' => 'DNS Misconfiguration Check',
            'short' => 'Looks for common domain name system settings that can cause problems.',
            'long'  => 'DNS translates names (example.com) into addresses. This scan finds common DNS errors (like open resolvers or missing protections) that could break services or be abused by attackers.'
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

        // SMB Share Scan
        if (in_array('smb_share_scan', $features)) {
            $script = base_path("python_scripts/SMBShareScan.py");

            [$result, $output] = $runPythonScript($script);
            $rawOutput .= "\n\n=== SMB Share Scan ===\n" . $output;
            
            // Store result in a separate key, not 'ports'
            $parsedResults['smb_shares'] = $result['shares'] ?? [];
        }

         //OS Fingerprinting
        if (in_array('os_fingerprinting', $features)) {
            $script = base_path("python_scripts/OSFingerPrinting.py");

            [$result, $output] = $runPythonScript($script);
            $rawOutput .= "\n\n=== OS Fingerprinting ===\n" . $output;
            
            // Store result in a separate key, not 'ports'
            $parsedResults['os_fingerprinting'] = $result['hosts'] ?? [];
        }

         //FTP Anonymous Login
        if (in_array('ftp_anonymous', $features)) {
            $script = base_path("python_scripts/FTPChecker.py");

            [$result, $output] = $runPythonScript($script);
            $rawOutput .= "\n\n=== FTP Anonymous Login Checker ===\n" . $output;
            
            // Store result in a separate key, not 'ports'
            $parsedResults['ftp_anonymous'] = $result['ftp'] ?? [];
        }

        //SSH weak config check
        
        if (in_array('ssh_weak_config', $features)) {
        
            $script = base_path("python_scripts/SSHChecker.py");
            [$result, $output] = $runPythonScript($script);
            $rawOutput .= "\n\n=== SSH Weak Configuration Checker ===\n" . $output;
            
            // Store result in a proper SSH key
            $parsedResults['ssh_weak_config'] = $result['ssh'] ?? [];
        }

        //NMap NSE Scan
        if (in_array('nmap_nse', $features)) {
        
            $script = base_path("python_scripts/NmapNseScanner.py");
            [$result, $output] = $runPythonScript($script);
            $rawOutput .= "\n\n=== Nmap NSE Scan ===\n" . $output;
            
            $parsedResults['nmap_nse'] = $result['nmap'] ?? [];
        }

        //Banner Grabbing
        if (in_array('banner_grabbing', $features)) {
    
            $script = base_path("python_scripts/BannerGrabber.py");
            [$result, $output] = $runPythonScript($script);
            $rawOutput .= "\n\n=== Banner Grabbing ===\n" . $output;
            $parsedResults['banner_grabbing'] = $result['banner'] ?? [];
        }

        
        //Nikto Web Scan (Simulated)
        if (in_array('nikto', $features)) {

            $script = base_path("python_scripts/Nikto.py");

            [$result, $output] = $runPythonScript($script);

            $rawOutput .= "\n\n=== Nikto Web Scan (Simulated) ===\n" . $output;

            // Save JSON results
            $parsedResults['nikto'] = $result['nikto'] ?? [];
        }

        //SSL/TLS SCAN
        if (in_array('ssl_tls', $features)) {

            $script = base_path("python_scripts/SslTls.py");

            [$result, $output] = $runPythonScript($script);

            $rawOutput .= "\n\n=== SSL/TLS SCAN ===\n" . $output;

            // Save JSON results
            $parsedResults['ssl_tls'] = $result['sslscan'] ?? [];
        }

        // Docker Misconfigurations Checker
        if (in_array('docker', $features)) {

            $script = base_path("python_scripts/DockerMisconfigChecker.py");

            [$result, $output] = $runPythonScript($script);
            $rawOutput .= "\n\n=== Docker Misconfigurations ===\n" . $output;

        
            $parsedResults['docker'] = $result['docker'] ?? [];
        }

        //Firewall Status
        if (in_array('firewall', $features)) {

            $script = base_path("python_scripts/FirewallCheck.py");

            [$result, $output] = $runPythonScript($script);
            $rawOutput .= "\n\n=== Firewall Status ===\n" . $output;

        
            $parsedResults['firewall'] = $result['firewall'] ?? [];
        }

        // Passive Network Sniffing
        if (in_array('passive_sniffing', $features)) {
            $script = base_path("python_scripts/passiveSniffer.py");
            $timeout = 15; // seconds
            [$result, $output] = $runPythonScript("$script $timeout");

            $rawOutput .= "\n\n=== Passive Network Sniffing ===\n" . $output;

            $parsedResults['passive_sniffing'] = $result['passive_sniffing'] ?? [
                'error' => 'No data captured or permission denied.'
            ];
        }

        //DNS Misconfiguration Check

        if (in_array('dns_misconfig', $features)) {

            $script = base_path("python_scripts/DNSMisconfig.py");

            [$result, $output] = $runPythonScript($script);
            $rawOutput .= "\n\n=== DNS Misconfiguration Check ===\n" . $output;

        
            $parsedResults['dns_misconfig'] = $result['dns'] ?? [];
        }


        // Generate AI summary ONCE and store it
        $aiSummary = GeminiService::summarize($rawOutput);

        // Save scan to database
        $scan = Scan::create([
            'user_id' => $user->id,
            'target' => $host,
            'scan_mode' => $scanMode,
            'auto_detect' => $autoDetect,
            'features' => $features,
            'parsed_results' => $parsedResults,
            'raw_output' => $rawOutput,
            'ai_summary' => $aiSummary, 
        ]);

        $scanNames = implode(', ', array_map(fn($f) => $f, $features));

        ActivityLogger::log(
            "Ran a scan",
            "Scans executed: {$scanNames}, Target: {$scan->target}, Mode: {$scan->scan_mode}"
        );



        //return redirect()->route('scan.reports')->with('success', "Scan #{$scan->id} completed!");
        return redirect()->route('scan.result', $scan->id)->with('success', "Scan #{$scan->id} completed!");


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

    // public function exportAllPdf()
    // {
    //     $scans = Scan::orderBy('created_at', 'desc')->get();

    //     $pdf = Pdf::loadView('reports.all_scans', compact('scans'))
    //             ->setPaper('A4', 'landscape');

    //     return $pdf->download("all_scan_reports.pdf");
    // }

public function exportAllPdf()
{
    $scans = Scan::orderBy('created_at', 'desc')->get();

    $pdf = Pdf::loadView('reports.all_scans', compact('scans'))
        ->setPaper('A4', 'landscape');

    return $pdf->download('all_scan_reports.pdf');
}





   public function showScanResult($id)
    {
        $scan = Scan::find($id);

        if (!$scan) {
            return redirect()->route('scan.reports')
                            ->with('error', 'Scan not found.');
        }

        // Generate DeepSeek AI summary
        $aiSummary = GeminiService::summarize($scan->raw_output);


        return view('profile.scanresult', [
            'scan' => $scan,
            'aiSummary' => $aiSummary,
        ]);
    }




    






    



}

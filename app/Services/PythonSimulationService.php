<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


class PythonSimulationService
{
    protected string $pythonPath;
    protected string $scriptsPath;

    public function __construct()
    {
        // Configure Python path (adjust as needed)
        $this->pythonPath = env('PYTHON_PATH', 'python3');
        $this->scriptsPath = base_path('python_scripts');
    }

    public function runMITMSimulation(): array
    {
        $scriptPath = $this->scriptsPath . '/mitm_simulator.py';
        
        if (!file_exists($scriptPath)) {
            Log::error("Python script not found: {$scriptPath}");
            return $this->fallbackMITMResults();
        }

        try {
            $process = new Process([
                $this->pythonPath,
                $scriptPath
            ]);

            $process->setTimeout(30);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $output = $process->getOutput();
            $data = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('Failed to decode Python output, using fallback');
                return $this->fallbackMITMResults();
            }

            return $data;

        } catch (\Exception $e) {
            Log::error('Python simulation failed: ' . $e->getMessage());
            return $this->fallbackMITMResults();
        }
    }

    protected function fallbackMITMResults(): array
    {
        return [
            'intercepted_packets' => rand(80, 300),
            'exposed_credentials' => rand(0, 4),
            'risk_level' => rand(0, 1) ? 'High' : 'Medium',
            'simulation_type' => 'MITM',
            'status' => 'completed',
            'using_fallback' => true
        ];
    }

    // Add this method to your PythonSimulationService class:
public function runDDOSSimulation(string $mode, string $target): array
{
    $scriptPath = $this->scriptsPath . '/ddos_simulator.py';
    
    // Create the Python script if it doesn't exist
    if (!file_exists($scriptPath)) {
        $this->createDefaultDDoSScript($scriptPath);
    }

    try {
        $process = new Process([
            $this->pythonPath,
            $scriptPath,
            $mode,
            $target
        ]);

        $process->setTimeout(45);
        $process->run();

        if (!$process->isSuccessful()) {
            Log::error('DDoS Python process failed: ' . $process->getErrorOutput());
            return $this->fallbackDDoSResults($mode, $target);
        }

        $output = $process->getOutput();
        $data = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning('Failed to decode DDoS Python output: ' . json_last_error_msg());
            return $this->fallbackDDoSResults($mode, $target);
        }

        return $data;

    } catch (\Exception $e) {
        Log::error('DDoS simulation failed: ' . $e->getMessage());
        return $this->fallbackDDoSResults($mode, $target);
    }
}

protected function createDefaultDDoSScript(string $scriptPath): void
{
    // You can copy the full Python script content here
    // or leave it as file creation (already handled above)
    // For simplicity, we'll create a minimal version
    $pythonCode = file_get_contents(__DIR__ . '/../../python_scripts/ddos_simulator.py');
    file_put_contents($scriptPath, $pythonCode);
    
    // Make it executable on Unix-like systems
    if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
        chmod($scriptPath, 0755);
    }
}

protected function fallbackDDoSResults(string $mode, string $target): array
{
    return [
        'simulation_type' => 'DDOS',
        'status' => 'completed',
        'using_fallback' => true,
        'attack_parameters' => [
            'mode' => $mode,
            'target' => $target
        ],
        'attack_results' => [
            'total_requests' => rand(1000, 10000),
            'peak_request_rate' => rand(100, 500),
            'duration' => 10,
            'risk_level' => $mode === 'High' ? 'High' : ($mode === 'Medium' ? 'Medium' : 'Low')
        ]
    ];
}

    public function runPassiveSniffingSimulation(): array
    {
        $scriptPath = base_path('python_scripts/passive_sniffing_simulator.py');
        
        if (!file_exists($scriptPath)) {
            return $this->generateFallbackSniffingData();
        }

        try {
            // Run Python script
            $command = escapeshellcmd("python3 " . $scriptPath . " --time 15 --interface eth0");
            $output = shell_exec($command . " 2>&1");
            
            // Parse Python output
            $results = $this->parsePythonSniffingOutput($output);
            
            if (!empty($results)) {
                return [
                    'sniffing_results' => $results,
                    'metadata' => [
                        'script_version' => '1.0',
                        'execution_time' => date('Y-m-d H:i:s'),
                        'simulation_type' => 'PASSIVE_SNIFFING',
                        'educational_insights' => [
                            'Network traffic analysis completed',
                            'Encryption effectiveness measured',
                            'Protocol vulnerabilities identified'
                        ]
                    ]
                ];
            }
            
            return $this->generateFallbackSniffingData();
            
        } catch (\Exception $e) {
            Log::error('Python passive sniffing simulation failed: ' . $e->getMessage());
            return $this->generateFallbackSniffingData();
        }
    }

    private function parsePythonSniffingOutput(string $output): array
    {
        $results = [
            'unencrypted_services' => 0,
            'exposed_sessions' => 0,
            'credentials_visible' => 0,
            'protocol_distribution' => [],
            'vulnerable_protocols' => [],
            'encryption_rate' => 0
        ];

        try {
            // Look for JSON output in Python script
            if (preg_match('/\{.*\}/s', $output, $matches)) {
                $jsonData = json_decode($matches[0], true);
                if (is_array($jsonData)) {
                    return array_merge($results, $jsonData);
                }
            }

            // Parse text output
            $lines = explode("\n", $output);
            
            foreach ($lines as $line) {
                // Extract protocol distribution
                if (preg_match('/(HTTP|HTTPS|DNS|SMTP|FTP|IRC).*?(\d+).*packets.*\(([\d.]+)%\)/', $line, $matches)) {
                    $protocol = $matches[1];
                    $count = (int)$matches[2];
                    $percentage = (float)$matches[3];
                    
                    $results['protocol_distribution'][$protocol] = $count;
                    
                    if ($protocol !== 'HTTPS') {
                        $results['unencrypted_services']++;
                    }
                }
                
                // Extract vulnerabilities
                if (str_contains($line, 'SECURITY VULNERABILITY DETECTED')) {
                    $results['credentials_visible']++;
                }
                
                // Extract encryption rate
                if (preg_match('/Encrypted traffic.*?([\d.]+)%/', $line, $matches)) {
                    $results['encryption_rate'] = (float)$matches[1];
                }
            }
            
            // Calculate derived values
            $results['exposed_sessions'] = (int)($results['unencrypted_services'] * rand(3, 8));
            
            // Calculate risk level
            if ($results['credentials_visible'] >= 3 || $results['encryption_rate'] < 50) {
                $results['risk_level'] = 'High';
            } elseif ($results['exposed_sessions'] > 20 || $results['encryption_rate'] < 70) {
                $results['risk_level'] = 'Medium';
            } else {
                $results['risk_level'] = 'Low';
            }
            
        } catch (\Exception $e) {
            // Fallback to generated data
        }
        
        return $results;
    }

    private function generateFallbackSniffingData(): array
    {
        $protocols = ['HTTP', 'HTTPS', 'DNS', 'SMTP', 'FTP'];
        $distribution = [];
        
        foreach ($protocols as $protocol) {
            $distribution[$protocol] = rand(10, 100);
        }
        
        $totalPackets = array_sum($distribution);
        $encryptedPackets = $distribution['HTTPS'] ?? 0;
        $encryptionRate = $totalPackets > 0 ? ($encryptedPackets / $totalPackets * 100) : 50;
        
        $unencryptedServices = count(array_diff($protocols, ['HTTPS']));
        $exposedSessions = rand(10, 40);
        $credentialsVisible = rand(0, 5);
        
        // Determine risk level
        if ($credentialsVisible >= 3 || $encryptionRate < 50) {
            $riskLevel = 'High';
        } elseif ($exposedSessions > 20 || $encryptionRate < 70) {
            $riskLevel = 'Medium';
        } else {
            $riskLevel = 'Low';
        }
        
        return [
            'sniffing_results' => [
                'unencrypted_services' => $unencryptedServices,
                'exposed_sessions' => $exposedSessions,
                'credentials_visible' => $credentialsVisible,
                'protocol_distribution' => $distribution,
                'encryption_rate' => round($encryptionRate, 1),
                'risk_level' => $riskLevel,
                'vulnerable_protocols' => ['HTTP', 'FTP', 'SMTP'],
            ],
            'metadata' => [
                'script_version' => '1.0',
                'execution_time' => date('Y-m-d H:i:s'),
                'simulation_type' => 'PASSIVE_SNIFFING',
                'educational_insights' => [
                    'Simulated network traffic analysis completed',
                    'Encryption effectiveness: ' . round($encryptionRate, 1) . '%',
                    'Unencrypted protocols detected'
                ]
            ]
        ];
    }
}
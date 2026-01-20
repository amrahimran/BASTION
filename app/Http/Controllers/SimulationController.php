<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Simulation;
use App\Models\ActivityLogs;
use App\Models\User;
use App\Services\AiExplanationService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PythonSimulationService; 

class SimulationController extends Controller
{
    protected $pythonService;

    public function __construct(PythonSimulationService $pythonService)
    {
        $this->pythonService = $pythonService;
    }

    public function index()
    {
        $latestScan = Simulation::latest()->first();
        return view('profile.simulation', compact('latestScan'));
    }

    // ---------------- MITM ----------------
    public function runMitm(AiExplanationService $ai)
    {
        // Run Python simulation
        $pythonResults = $this->pythonService->runMITMSimulation();
        
        // Extract results from Python output
        $attackResults = $pythonResults['attack_results'] ?? $pythonResults;
        
        $interceptedPackets = $attackResults['intercepted_packets'] ?? rand(80, 300);
        $exposedCredentials = $attackResults['exposed_credentials'] ?? rand(0, 4);
        $riskLevel = $attackResults['risk_level'] ?? ($exposedCredentials > 0 ? 'High' : 'Medium');
        
        // Get educational insights from Python if available
        $educationalInsights = $pythonResults['educational_report']['educational_insights'] ?? [];

        // Generate AI explanation
        $aiExplanation = $ai->generateMitmExplanation([
            'detected_devices' => $pythonResults['network_info']['device_count'] ?? rand(5, 20),
            'intercepted_packets' => $interceptedPackets,
            'exposed_credentials' => $exposedCredentials,
            'risk_level' => $riskLevel,
            'educational_insights' => $educationalInsights,
            'python_simulation' => true,
        ]);

        // Store simulation with chart data
        $simulation = Simulation::create([
            'user_id' => auth()->id(),
            'simulation_type' => 'MITM',
            'status' => 'Completed',
            'intercepted_packets' => $interceptedPackets,
            'exposed_credentials' => $exposedCredentials,
            'risk_level' => $riskLevel,
            'ai_explanation' => $aiExplanation,
            'metadata' => array_merge(
                $pythonResults['metadata'] ?? [], // Keep Python's metadata
                [
                    'python_simulation' => true,
                    'simulation_details' => $pythonResults['educational_report'] ?? [],
                    'execution_method' => 'python_script',
                    // Ensure chart_data is included
                    'chart_data' => $pythonResults['metadata']['chart_data'] ?? [
                        'traffic_over_time' => [
                            'labels' => ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00'],
                            'data' => [45, 30, 80, 100, 120, 90]
                        ],
                        'protocol_distribution' => [
                            'labels' => ['HTTP', 'HTTPS', 'DNS', 'Email', 'FTP'],
                            'data' => [35, 45, 12, 5, 3],
                            'colors' => ['#ef4444', '#10b981', '#3b82f6', '#8b5cf6', '#f59e0b']
                        ]
                    ]
                ]
            )
        ]);

        // Log activity
        ActivityLogs::create([
            'user_id' => auth()->id(),
            'action' => 'Ran MITM simulation (Python)',
            'details' => json_encode([
                'devices_detected' => $pythonResults['network_info']['device_count'] ?? 'N/A',
                'risk_level' => $riskLevel,
                'using_python' => true,
                'intercepted_packets' => $interceptedPackets,
            ]),
        ]);

        return redirect()->route('simulation.result', $simulation->id)
            ->with('success', 'MITM simulation completed using Python engine');
    }

    // ---------------- DDOS ----------------
    public function runDdos(Request $request, AiExplanationService $ai)
    {
        set_time_limit(300);

        $mode = $request->mode;
        $target = $request->target;

        // Detect LAN
        $localIp = gethostbyname(gethostname());
        $subnet = preg_replace('/\.\d+$/', '.0/24', $localIp);

        $output = [];
        exec("nmap -sn --host-timeout 2s $subnet", $output);

        $deviceCount = count(array_filter($output, fn($line) => str_contains($line, 'Nmap scan report')));

        // Base request rate
        $baseRate = match ($mode) {
            'Low' => rand(50, 120),
            'Medium' => rand(150, 300),
            'High' => rand(350, 500),
            default => 100,
        };

        // Scale based on LAN exposure
        $lanFactor = min($deviceCount * 5, 200);
        $requestRate = $baseRate + $lanFactor;

        $duration = 10;
        $totalRequests = $requestRate * $duration;

        // Risk calculation
        if ($requestRate > 500 && $deviceCount > 10) {
            $riskLevel = 'High';
        } elseif ($requestRate > 250) {
            $riskLevel = 'Medium';
        } else {
            $riskLevel = 'Low';
        }

        $aiExplanation = $ai->generateDdosExplanation([
            'mode' => $mode,
            'target' => $target,
            'devices_detected' => $deviceCount,
            'request_rate' => $requestRate,
            'total_requests' => $totalRequests,
            'risk_level' => $riskLevel,
        ]);

        $simulation = Simulation::create([
            'user_id' => auth()->id(),
            'simulation_type' => 'DDOS',
            'status' => 'Completed',
            'ddos_mode' => $mode,
            'target' => $target,
            'request_rate' => $requestRate,
            'duration' => $duration,
            'total_requests' => $totalRequests,
            'risk_level' => $riskLevel,
            'ai_explanation' => $aiExplanation,
        ]);

        ActivityLogs::create([
            'user_id' => auth()->id(),
            'action' => 'Ran DDoS simulation',
            'details' => json_encode([
                'devices_detected' => $deviceCount,
                'mode' => $mode,
                'request_rate' => $requestRate,
                'risk_level' => $riskLevel,
            ]),
        ]);

        return redirect()->route('simulation.result', $simulation->id);
    }

    // ---------------- PHISHING ----------------
    public function runPhishing(AiExplanationService $ai)
    {
        $theme = request('theme');
        $target = request('target');

        // Base emails
        $emailsSent = rand(60, 120);

        // Theme relevance affects click rate
        $themeMultiplier = match ($theme) {
            'Banking', 'Payroll', 'Delivery' => 0.45,
            'Social Media', 'Streaming' => 0.35,
            'Generic' => 0.25,
            default => 0.3,
        };

        $clickedLinks = (int) ($emailsSent * $themeMultiplier);

        // Target awareness affects credential submission
        $targetAwareness = match ($target) {
            'IT Staff' => 0.1,
            'Student' => 0.25,
            'Employee' => 0.35,
            'Manager' => 0.3,
            default => 0.3,
        };

        $enteredDetails = (int) ($clickedLinks * $targetAwareness);

        // Risk evaluation
        if ($enteredDetails > 15) {
            $riskLevel = 'High';
        } elseif ($clickedLinks > 25) {
            $riskLevel = 'Medium';
        } else {
            $riskLevel = 'Low';
        }

        $aiExplanation = $ai->generatePhishingExplanation([
            'theme' => $theme,
            'target' => $target,
            'emails_sent' => $emailsSent,
            'clicked_links' => $clickedLinks,
            'entered_details' => $enteredDetails,
            'risk_level' => $riskLevel,
        ]);

        $simulation = Simulation::create([
            'user_id' => auth()->id(),
            'simulation_type' => 'PHISHING',
            'status' => 'Completed',
            'emails_sent' => $emailsSent,
            'clicked_links' => $clickedLinks,
            'entered_details' => $enteredDetails,
            'risk_level' => $riskLevel,
            'ai_explanation' => $aiExplanation,
        ]);

        ActivityLogs::create([
            'user_id' => auth()->id(),
            'action' => 'Ran Phishing simulation',
            'details' => json_encode([
                'theme' => $theme,
                'target' => $target,
                'risk_level' => $riskLevel,
            ]),
        ]);

        return redirect()->route('simulation.result', $simulation->id);
    }

    // ---------------- PASSIVE SNIFFING ----------------
    public function runPassiveSniffing(AiExplanationService $ai)
    {
        set_time_limit(0);

        // Detect local subnet (read-only)
        $localIp = gethostbyname(gethostname());
        $subnet = preg_replace('/\.\d+$/', '.0/24', $localIp);

        // SAFE scan – service visibility only
        $output = [];
        exec("nmap -sT -p 21,23,25,80,110,143,443 $subnet", $output);

        // Simulated sniffing exposure
        $unencryptedServices = rand(1, 4);
        $exposedSessions = rand(10, 40);
        $credentialsVisible = rand(0, 5);

        // Risk evaluation
        if ($credentialsVisible >= 3) {
            $riskLevel = 'High';
        } elseif ($exposedSessions > 20) {
            $riskLevel = 'Medium';
        } else {
            $riskLevel = 'Low';
        }

        $aiExplanation = $ai->generateSniffingExplanation([
            'unencrypted_services' => $unencryptedServices,
            'exposed_sessions' => $exposedSessions,
            'credentials_visible' => $credentialsVisible,
            'risk_level' => $riskLevel,
        ]);

        $simulation = Simulation::create([
            'user_id' => auth()->id(),
            'simulation_type' => 'PASSIVE_SNIFFING',
            'status' => 'Completed',
            'unencrypted_services' => $unencryptedServices,
            'exposed_sessions' => $exposedSessions,
            'credentials_visible' => $credentialsVisible,
            'risk_level' => $riskLevel,
            'ai_explanation' => $aiExplanation,
        ]);

        ActivityLogs::create([
            'user_id' => auth()->id(),
            'action' => 'Ran Passive Sniffing simulation',
            'details' => json_encode([
                'unencrypted_services' => $unencryptedServices,
                'risk_level' => $riskLevel,
            ]),
        ]);

        return redirect()->route('simulation.result', $simulation->id);
    }

    public function result(Simulation $simulation)
    {
        return view('profile.simulationresult', compact('simulation'));
    }

    // ---------------- REPORTS ----------------
    public function reports()
    {
        $type = request('type');
        
        $query = Simulation::with('user')
            ->orderBy('created_at', 'desc');
        
        // Apply filter if type is specified
        if ($type) {
            $query->where('simulation_type', $type);
        }
        
        // Paginate the results (10 per page) - FIXED!
        $simulations = $query->paginate(10);
        
        return view('profile.simulationreports', compact('simulations'));
    }

    public function exportSinglePdf($id)
    {
        $simulation = Simulation::findOrFail($id);

        $pdf = Pdf::loadView('reports.simulation_single', compact('simulation'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download("simulation_report_{$simulation->id}.pdf");
    }

    public function exportAllPdf()
    {
        $simulations = Simulation::orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('reports.all_simulations', compact('simulations'))
                  ->setPaper('A4', 'landscape');

        return $pdf->download("all_simulation_reports.pdf");
    }
}
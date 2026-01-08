<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Simulation;
use App\Models\ActivityLogs;
use App\Models\User;
use App\Services\AiExplanationService;
use Barryvdh\DomPDF\Facade\Pdf;

class SimulationController extends Controller
{
    public function index()
    {
        $latestScan = Simulation::latest()->first();
        return view('profile.simulation', compact('latestScan'));
    }

    // ---------------- MITM ----------------
   public function runMitm(AiExplanationService $ai)
{
    set_time_limit(0);

    // Detect local subnet
    $localIp = gethostbyname(gethostname());
    $subnet = preg_replace('/\.\d+$/', '.0/24', $localIp);

    // Fast LAN scan (safe)
    $output = [];
    exec("nmap -sn --host-timeout 2s $subnet", $output);

    $deviceCount = count(array_filter($output, fn($line) => str_contains($line, 'Nmap scan report')));

    // Simulated MITM logic
    $interceptedPackets = rand(80, 300) + ($deviceCount * 5);
    $exposedCredentials = $deviceCount > 5 ? rand(1, 4) : rand(0, 1);
    $riskLevel = $exposedCredentials > 0 ? 'High' : 'Medium';

    $aiExplanation = $ai->generateMitmExplanation([
        'detected_devices' => $deviceCount,
        'intercepted_packets' => $interceptedPackets,
        'exposed_credentials' => $exposedCredentials,
        'risk_level' => $riskLevel,
    ]);

    $simulation = Simulation::create([
        'user_id' => auth()->id(),
        'simulation_type' => 'MITM',
        'status' => 'Completed',
        'intercepted_packets' => $interceptedPackets,
        'exposed_credentials' => $exposedCredentials,
        'risk_level' => $riskLevel,
        'ai_explanation' => $aiExplanation,
    ]);

    ActivityLogs::create([
        'user_id' => auth()->id(),
        'action' => 'Ran MITM simulation',
        'details' => json_encode([
            'devices_detected' => $deviceCount,
            'risk_level' => $riskLevel,
        ]),
    ]);

    return redirect()->route('simulation.result', $simulation->id);
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

public function result(Simulation $simulation)
{
    return view('profile.simulationresult', compact('simulation'));
}


    // ---------------- REPORTS ----------------
    public function reports()
    {
        $simulations = Simulation::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

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

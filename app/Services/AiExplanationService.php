<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiExplanationService
{
//     public function generateMitmExplanation(array $data): string
//     {
//         $prompt = "
// Explain the following Man-in-the-Middle (MITM) simulation result
// to NON-TECHNICAL office staff.

// Simulation Details:
// - Intercepted network traffic: {$data['intercepted_packets']}
// - Credentials exposed: {$data['exposed_credentials']}
// - Risk level: {$data['risk_level']}

// Explain clearly using:
// - Short paragraphs
// - HTML-friendly bullet points <ul><li>
// - Highlight key items using <strong>
// - Avoid technical jargon

// Content should be easy to read for non-technical users.
// ";

//         $response = Http::post(
//             'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key='
//             . config('services.gemini.key'),
//             [
//                 'contents' => [
//                     [
//                         'parts' => [
//                             ['text' => $prompt]
//                         ]
//                     ]
//                 ]
//             ]
//         );

//         return $response->json('candidates.0.content.parts.0.text')
//             ?? 'AI explanation could not be generated.';
//     }

   
public function generateMitmExplanation(array $data): string
{
    $prompt = "
Audience: Non-technical staff.

Explain a Man-in-the-Middle attack simulation.

Results:
Devices detected on the network: {$data['detected_devices']}
Data packets observed: {$data['intercepted_packets']}
Login details exposed: {$data['exposed_credentials']}
Risk level: {$data['risk_level']}

Explain:
- What MITM means
- Why data can be seen
- Why encryption matters
- How staff reduce risk

Use short paragraphs.
Plain English.
No bullet points.
No headings.
";

    $response = Http::post(
        'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key='
        . config('services.gemini.key'),
        [
            'contents' => [[
                'parts' => [['text' => $prompt]]
            ]]
        ]
    );

    return trim(
        $response->json('candidates.0.content.parts.0.text')
        ?? 'Explanation unavailable.'
    );
}

public function generateDdosExplanation(array $data): string
    {
        $prompt = "
Explain the following Distributed Denial-of-Service (DDoS) simulation
to NON-TECHNICAL office staff.

Simulation Details:
- Attack strength: {$data['mode']}
- Target system: {$data['target']}
- Requests per second: {$data['request_rate']}
- Total requests sent: {$data['total_requests']}
- Risk level: {$data['risk_level']}

Please format the output with:
- Short, easy-to-read paragraphs
- Bullet points using HTML <ul><li>
- Highlight important terms with <strong>
- Use color tags if helpful: e.g., <span style='color:#ff4d4d'>High Risk</span>
- Do NOT include markdown symbols like # or **

Explain:
1. What a DDoS attack is in simple terms
2. What happened in this simulation
3. Why downtime is dangerous
4. How this affects employees and customers
5. Basic prevention ideas (firewalls, rate limiting, monitoring)

Make the explanation clean, educational, and visually scannable.
";

        $response = Http::post(
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key='
            . config('services.gemini.key'),
            [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]
        );

        return $response->json('candidates.0.content.parts.0.text')
            ?? 'AI explanation could not be generated.';
    }

    public function generatePhishingExplanation(array $data): string
{
    $prompt = "
You are writing content that will be shown directly on a website.

IMPORTANT RULES:
- DO NOT use markdown
- DO NOT use ###, ---, *, or bullet symbols
- DO NOT bold using ** **
- Use short paragraphs
- Leave a blank line between sections
- Write in plain English only

Audience: Non-technical office staff

Title: Phishing Awareness Simulation Result

Explain clearly using this structure.

Each section MUST be no more than 3 short sentences.
Do NOT repeat ideas.
Do NOT explain cybersecurity theory.
Focus only on what THIS simulation shows.


SECTION TITLE: What is a phishing email?
Explain in 2–3 simple sentences.

SECTION TITLE: What happened in this simulation?
Include these facts naturally:
Theme: {$data['theme']}
Target group: {$data['target']}
Emails sent: {$data['emails_sent']}
Users who clicked links: {$data['clicked_links']}
Users who entered fake login details: {$data['entered_details']}
Risk level: {$data['risk_level']}

SECTION TITLE: Why this is dangerous
Explain impact on staff and company in simple terms.

SECTION TITLE: How to stay safe
Give 4–5 very simple tips.

No emojis.
No lists.
No technical jargon.

End the response with ONE short sentence summarizing the overall risk.

";

    $response = Http::post(
        'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key='
        . config('services.gemini.key'),
        [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]
    );

    return $response->json('candidates.0.content.parts.0.text')
        ?? 'AI explanation could not be generated.';
}

public function generateSniffingExplanation(array $data): string
{
    $prompt = "
        Audience: Non-technical office staff

        You are explaining the result of a Passive Network Sniffing security simulation.
        This is an awareness exercise, not a real attack.

        Rules:
        Use plain English only.
        Keep sentences short.
        No technical jargon.
        No bullet points.
        No markdown.
        Add a blank line between each section.
        Do not mention instructions or rules.

        Write the response using EXACTLY the following format and headings.

        What happened in this simulation?
        Passive sniffing means quietly listening to network traffic.
        It is like overhearing conversations in a public place.
        No systems were hacked or broken into.

        What information could be seen?
        Unencrypted services detected: {$data['unencrypted_services']}
        Visible network sessions: {$data['exposed_sessions']}
        Login details potentially visible: {$data['credentials_visible']}

        Why is this a risk?
        Seeing this information can expose private company data.
        It can damage trust with staff and customers.
        Sensitive business information could be misused.

        How does encryption help?
        Encryption protects information by scrambling it.
        Even if someone listens, they cannot understand it.
        Only the intended receiver can read the data.

        What should the company do?
        Secure all Wi-Fi networks.
        Enforce HTTPS on all systems.
        Use VPNs for remote access.
        Improve staff awareness.

        Overall risk level: {$data['risk_level']}
        ";


   try {
    $response = Http::timeout(20)->post(
        'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key='
        . config('services.gemini.key'),
        [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ]
        ]
    );

    if ($response->failed()) {
        throw new \Exception('AI request failed');
    }

    return $response->json()['candidates'][0]['content']['parts'][0]['text']
        ?? 'Simulation completed. AI explanation is currently unavailable.';

} catch (\Exception $e) {

    // ✅ FALLBACK – app continues normally
    return "
Security Explanation

What happened in this simulation?
Passive sniffing means quietly observing network traffic without interfering.

What information could be exposed?
Unencrypted data, visible sessions, and login details.

Why is this a risk?
Sensitive company information could be viewed or misused.

How does encryption help?
Encryption ensures intercepted data cannot be understood.

What should the company do?
Use HTTPS, secure Wi-Fi, apply VPNs, and train staff.

Overall risk level: {$data['risk_level']}
";
}

}


}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiExplanationService
{
    public function generateMitmExplanation(array $data): string
    {
        $prompt = "
Explain the following Man-in-the-Middle (MITM) simulation result
to NON-TECHNICAL office staff.

Simulation Details:
- Intercepted network traffic: {$data['intercepted_packets']}
- Credentials exposed: {$data['exposed_credentials']}
- Risk level: {$data['risk_level']}

Explain clearly using:
- Short paragraphs
- HTML-friendly bullet points <ul><li>
- Highlight key items using <strong>
- Avoid technical jargon

Content should be easy to read for non-technical users.
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

}

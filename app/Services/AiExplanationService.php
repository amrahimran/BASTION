<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiExplanationService
{
    public function generateMitmExplanation(array $data): string
    {
        $prompt = "
        AUDIENCE: Non-technical office staff who use computers but aren't IT experts.

        Explain a Man-in-the-Middle attack simulation in PLAIN ENGLISH.

        SIMULATION RESULTS:
        - Devices on network: {$data['detected_devices']}
        - Data packets that could be seen: {$data['intercepted_packets']}
        - Login details that could be exposed: {$data['exposed_credentials']}
        - Risk level: {$data['risk_level']}

        IMPORTANT FORMAT RULES:
        1. Use PLAIN TEXT ONLY - NO HTML TAGS AT ALL
        2. NO <strong>, NO <p>, NO <br>, NO <ul>, NO <li>
        3. NO markdown symbols like *, #, -, >
        4. Use double line breaks between paragraphs
        5. Use normal sentences with periods
        6. NO bullet points, NO numbered lists
        7. NO section headings or titles
        8. If you need emphasis, just use capital letters or say 'important'

        EXPLAIN:
        - What is happening in simple terms
        - Why this matters for office workers
        - What staff should look out for
        - How to stay safe
        - Why this simulation was run

        Make it friendly, conversational, and helpful.
        OUTPUT MUST BE PLAIN TEXT WITH NO FORMATTING.
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

        $text = trim(
            $response->json('candidates.0.content.parts.0.text')
            ?? 'This simulation shows how data can be intercepted on unsecured networks. It helps us understand security risks that could affect our company.'
        );

        // CLEAN UP ANY REMAINING HTML/MARKDOWN
        return $this->cleanText($text);
    }

    public function generateDdosExplanation(array $data): string
    {
        $prompt = "
        AUDIENCE: Non-technical office staff. They know websites can be slow but don't know why.

        Explain a DDoS attack simulation in SIMPLE TERMS.

        SIMULATION RESULTS:
        - Attack type: {$data['mode']}
        - Target: {$data['target']}
        - Requests per second: {$data['request_rate']}
        - Total fake requests: {$data['total_requests']}
        - Risk level: {$data['risk_level']}

        IMPORTANT FORMAT RULES:
        1. Use PLAIN TEXT ONLY - NO HTML TAGS AT ALL
        2. NO <strong>, NO <p>, NO <br>, NO <ul>, NO <li>
        3. NO markdown symbols like *, #, -, >
        4. Use double line breaks between paragraphs
        5. Use normal sentences with periods
        6. NO bullet points, NO numbered lists
        7. NO section headings or titles

        EXPLAIN:
        - What a DDoS attack is (simple analogy)
        - What happens to websites/services during attack
        - How this affects staff work
        - How this affects customers
        - Why we run these simulations

        Make it practical and relatable to daily work.
        OUTPUT MUST BE PLAIN TEXT WITH NO FORMATTING.
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

        $text = $response->json('candidates.0.content.parts.0.text')
            ?? 'This simulation shows what happens when too many fake requests overwhelm our systems. Websites become slow or unavailable, affecting both staff and customers.';

        // CLEAN UP ANY REMAINING HTML/MARKDOWN
        return $this->cleanText($text);
    }

    public function generatePhishingExplanation(array $data): string
    {
        $prompt = "
        AUDIENCE: Office staff who receive emails daily. They're not security experts.

        Explain a phishing email simulation in EVERYDAY LANGUAGE.

        SIMULATION FACTS:
        - Email theme: {$data['theme']}
        - Target group: {$data['target']}
        - Fake emails sent: {$data['emails_sent']}
        - Staff who clicked links: {$data['clicked_links']}
        - Staff who entered fake details: {$data['entered_details']}
        - Risk level: {$data['risk_level']}

        IMPORTANT FORMAT RULES:
        1. Use PLAIN TEXT ONLY - NO HTML TAGS AT ALL
        2. NO <strong>, NO <p>, NO <br>, NO <ul>, NO <li>
        3. NO markdown symbols like *, #, -, >
        4. Use double line breaks between paragraphs
        5. Use normal sentences with periods
        6. NO bullet points, NO numbered lists
        7. NO section headings or titles

        EXPLAIN:
        - What phishing emails look like
        - Why people sometimes click them
        - What happens if someone enters details
        - How this affects the company
        - What to do if you see a suspicious email
        - Why we run these tests

        Make it feel like helpful advice from a colleague, not a technical lecture.
        OUTPUT MUST BE PLAIN TEXT WITH NO FORMATTING.
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

        $text = $response->json('candidates.0.content.parts.0.text')
            ?? 'This simulation tests how well staff can spot fake emails. Phishing emails try to trick people into clicking links or entering login details. Even a few clicks can put company information at risk.';

        // CLEAN UP ANY REMAINING HTML/MARKDOWN
        return $this->cleanText($text);
    }

    public function generateSniffingExplanation(array $data): string
    {
        $prompt = "
        AUDIENCE: Office staff who use Wi-Fi and company networks daily.

        Explain a passive network sniffing simulation in SIMPLE TERMS.

        SIMULATION RESULTS:
        - Unsecured services: {$data['unencrypted_services']}
        - Visible network sessions: {$data['exposed_sessions']}
        - Login details potentially visible: {$data['credentials_visible']}
        - Risk level: {$data['risk_level']}

        IMPORTANT FORMAT RULES:
        1. Use PLAIN TEXT ONLY - NO HTML TAGS AT ALL
        2. NO <strong>, NO <p>, NO <br>, NO <ul>, NO <li>
        3. NO markdown symbols like *, #, -, >
        4. Use double line breaks between paragraphs
        5. Use normal sentences with periods
        6. NO bullet points, NO numbered lists
        7. NO section headings or titles

        EXPLAIN:
        - What 'passive sniffing' means in simple terms
        - What kinds of information might be visible
        - Why this matters for company privacy
        - How to make information more private
        - Why we run these simulations

        Make it clear, practical, and focused on protecting company information.
        OUTPUT MUST BE PLAIN TEXT WITH NO FORMATTING.
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

            $text = $response->json()['candidates'][0]['content']['parts'][0]['text']
                ?? 'This simulation shows what information might be visible on unsecured networks. Like overhearing conversations, some network data can be seen by others if not properly protected.';

            // CLEAN UP ANY REMAINING HTML/MARKDOWN
            return $this->cleanText($text);

        } catch (\Exception $e) {
            return "This security simulation shows what information might be visible on company networks when not properly secured. It helps us understand what data needs extra protection to keep company information private.";
        }
    }

    /**
     * Clean text by removing all HTML tags and markdown formatting
     */
    private function cleanText(string $text): string
    {
        // Remove ALL HTML tags
        $text = strip_tags($text);
        
        // Remove markdown symbols
        $text = str_replace(['*', '#', '**', '__', '~~', '`'], '', $text);
        
        // Remove markdown list symbols
        $text = preg_replace('/^\s*[-*+]\s+/m', '', $text);
        $text = preg_replace('/^\s*\d+\.\s+/m', '', $text);
        
        // Remove angle brackets (sometimes AI uses <like this> for emphasis)
        $text = preg_replace('/<[^>]+>/', '', $text);
        
        // Remove excessive line breaks
        $text = preg_replace('/\n\s*\n\s*\n+/', "\n\n", $text);
        
        // Trim whitespace
        $text = trim($text);
        
        return $text;
    }
}
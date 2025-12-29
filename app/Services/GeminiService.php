<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    public static function summarize(string $rawOutput): string
    {
        if (empty(trim($rawOutput))) {
            return 'No scan data available for analysis.';
        }

        try {
            $response = Http::post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . env('GEMINI_API_KEY'),
                [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' =>
"Summarize the following network scan results for a NON-TECHNICAL user.

Rules:
- NO markdown
- NO bullet points
- NO stars or hashes
- Use short paragraphs
- Clearly label risk as HIGH RISK, MEDIUM RISK, or LOW RISK
- Give simple security advice

SCAN OUTPUT:
{$rawOutput}"
                                ]
                            ]
                        ]
                    ]
                ]
            );

            if (!$response->successful()) {
                Log::error('Gemini API error', ['body' => $response->body()]);
                return 'AI summary unavailable due to API error.';
            }

            $text = $response->json('candidates.0.content.parts.0.text')
                ?? 'AI summary could not be generated.';

            return self::formatForUI($text);

        } catch (\Exception $e) {
            Log::error('Gemini Exception', ['error' => $e->getMessage()]);
            return 'AI summary failed due to a system error.';
        }
    }

    /**
     * Format AI output for clean UI + risk highlighting
     */
    private static function formatForUI(string $text): string
    {
        // Escape first for safety
        $text = e(trim($text));

        // Remove markdown junk
        $text = preg_replace('/[\*\#•]+/', '', $text);

        // Fix excessive spacing
        $text = preg_replace("/\n{2,}/", "\n", $text);

        // Split into sentences
        $sentences = preg_split('/(?<=[.!?])\s+/', $text);

        $output = '';

        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if ($sentence === '') continue;

            // HIGH RISK (any wording)
            if (preg_match('/\b(high)\b.*\brisk\b|\brisk\b.*\b(high)\b/i', $sentence)) {
                $output .= '
                <div class="bg-red-600/20 border border-red-500 text-red-400 px-4 py-2 rounded-lg mb-3 font-semibold">
                    🔴 ' . $sentence . '
                </div>';
            }

            // MEDIUM RISK (any wording)
            elseif (preg_match('/\b(medium|moderate)\b.*\brisk\b|\brisk\b.*\b(medium|moderate)\b/i', $sentence)) {
                $output .= '
                <div class="bg-yellow-500/20 border border-yellow-400 text-yellow-300 px-4 py-2 rounded-lg mb-3 font-semibold">
                    🟡 ' . $sentence . '
                </div>';
            }

            // LOW RISK (any wording)
            elseif (preg_match('/\b(low|minimal)\b.*\brisk\b|\brisk\b.*\b(low|minimal)\b/i', $sentence)) {
                $output .= '
                <div class="bg-green-500/20 border border-green-400 text-green-300 px-4 py-2 rounded-lg mb-3 font-semibold">
                    🟢 ' . $sentence . '
                </div>';
            }

            // Normal sentence
            else {
                $output .= '<p class="text-gray-200 mb-2 leading-relaxed">'
                        . $sentence .
                        '</p>';
            }
        }

        return $output;
    }

}

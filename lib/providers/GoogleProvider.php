<?php
require_once __DIR__ . '/../interfaces/AIProviderInterface.php';

class GoogleProvider implements AIProviderInterface {
    private string $apiKey;
    private string $model = 'gemini-1.5-flash'; // ← Admin can change here

    public function __construct(string $apiKey) {
        $this->apiKey = $apiKey;
    }

    public function getSuggestion(array $data): string {
        $prompt = $this->buildPrompt($data);
        $url = "https://generativelanguage.googleapis.com/v1/models/{$this->model}:generateContent?key=" . $this->apiKey;

        $payload = json_encode([
            'contents' => [['parts' => [['text' => $prompt]]]],
            'generationConfig' => ['maxOutputTokens' => 500, 'temperature' => 0.2]
        ]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $payload
        ]);

        $response = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http !== 200) return "⚠️ Gemini failed (HTTP $http).";

        $json = json_decode($response, true);
        return $json['candidates'][0]['content']['parts'][0]['text'] ?? 'No response.';
    }

    private function buildPrompt(array $d): string {
        return "You are an SEO expert. Analyze:\n" .
               "Title: \"{$d['title']}\"\n" .
               "Meta: \"{$d['meta_description']}\"\n" .
               "H1s: {$d['h1_count']}\n" .
               "Keywords: " . implode(', ', array_keys($d['top_keywords'])) . "\n" .
               "Issues: " . implode('; ', $d['issues']) . "\n\n" .
               "Give 3 bullet-point improvements (max 100 words).";
    }
}
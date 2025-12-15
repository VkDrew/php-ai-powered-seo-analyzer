<?php
require_once __DIR__ . '/../interfaces/AIProviderInterface.php';

class GroqProvider implements AIProviderInterface {
    private string $apiKey;
    private string $model = 'llama-3.1-8b-instant'; // Very fast & free

    public function __construct(string $apiKey) {
        $this->apiKey = $apiKey;
    }

    public function getSuggestion(array $data): string {
        $prompt = $this->buildPrompt($data);
        $url = 'https://api.groq.com/openai/v1/chat/completions';

        $payload = json_encode([
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are an SEO expert. Be concise and actionable.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => 300,
            'temperature' => 0.3
        ]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey
            ],
            CURLOPT_POSTFIELDS => $payload
        ]);

        $response = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http !== 200) return "⚠️ Groq failed (HTTP $http).";

        $json = json_decode($response, true);
        return $json['choices'][0]['message']['content'] ?? 'No response.';
    }

    private function buildPrompt(array $d): string {
        return "SEO Analysis:\n" .
               "Title: \"{$d['title']}\" (length: " . strlen($d['title']) . ")\n" .
               "Meta: \"{$d['meta_description']}\" (length: " . strlen($d['meta_description']) . ")\n" .
               "H1 tags: {$d['h1_count']}\n" .
               "Top keywords: " . implode(', ', array_keys($d['top_keywords'])) . "\n" .
               "Issues: " . implode('; ', $d['issues']) . "\n\n" .
               "Provide 3 specific, actionable SEO improvements (max 100 words).";
    }
}
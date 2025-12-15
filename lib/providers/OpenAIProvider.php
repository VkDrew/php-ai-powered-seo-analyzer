<?php
require_once __DIR__ . '/../interfaces/AIProviderInterface.php';

class OpenAIProvider implements AIProviderInterface {
    private string $apiKey;
    private string $model = 'gpt-4o-mini'; // ← Admin can change here

    public function __construct(string $apiKey) {
        $this->apiKey = $apiKey;
    }

    public function getSuggestion(array $data): string {
        $prompt = $this->buildPrompt($data);
        $url = 'https://api.openai.com/v1/chat/completions';

        $payload = json_encode([
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are an SEO expert. Be concise and actionable.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => 250,
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

        if ($http !== 200) return "⚠️ OpenAI failed (HTTP $http).";

        $json = json_decode($response, true);
        return $json['choices'][0]['message']['content'] ?? 'No response.';
    }

    private function buildPrompt(array $d): string {
        return "SEO Data:\n- Title: \"{$d['title']}\"\n" .
               "- Meta desc: \"{$d['meta_description']}\"\n" .
               "- H1 count: {$d['h1_count']}\n" .
               "- Top keywords: " . implode(', ', array_keys($d['top_keywords'])) . "\n" .
               "- Issues: " . implode('; ', $d['issues']) . "\n\n" .
               "Suggest 3 concrete SEO fixes (under 100 words).";
    }
}
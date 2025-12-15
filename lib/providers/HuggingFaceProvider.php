<?php
require_once __DIR__ . '/../interfaces/AIProviderInterface.php';

class HuggingFaceProvider implements AIProviderInterface {
    private string $apiUrl;
    private ?string $apiToken;

    public function __construct(string $apiUrl, ?string $apiToken = null) {
        $this->apiUrl = rtrim($apiUrl, '/');
        $this->apiToken = $apiToken;
    }

    public function getSuggestion(array $data): string {
        $url = $this->apiUrl . '/analyze';

        $payload = json_encode([
            'title' => $data['title'],
            'meta_description' => $data['meta_description'],
            'h1_count' => $data['h1_count'],
            'top_keywords' => $data['top_keywords'],
            'issues' => $data['issues']
        ]);

        // Build headers - only add Authorization if token is provided
        $headers = ['Content-Type: application/json'];
        
        if (!empty($this->apiToken)) {
            $headers[] = 'Authorization: Bearer ' . $this->apiToken;
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_TIMEOUT => 60,  // Increased from 30 to 60 seconds
            CURLOPT_CONNECTTIMEOUT => 10  // Connection timeout
        ]);

        $response = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return "⚠️ HuggingFace API Error: " . $error;
        }

        if ($http !== 200) {
            return "⚠️ HuggingFace API failed (HTTP $http).";
        }

        $json = json_decode($response, true);
        
        if (!$json || !isset($json['success']) || !$json['success']) {
            $errorMsg = $json['error'] ?? 'Unknown error';
            return "⚠️ HuggingFace API Error: " . $errorMsg;
        }

        return $json['suggestion'] ?? 'No suggestion returned.';
    }
}
<?php
require_once __DIR__ . '/EnvLoader.php';
require_once __DIR__ . '/providers/GoogleProvider.php';
require_once __DIR__ . '/providers/OpenAIProvider.php';
require_once __DIR__ . '/providers/GroqProvider.php';

class AIService {
    private AIProviderInterface $provider;

    public function __construct() {
        EnvLoader::load();
        $ai = EnvLoader::getAIProvider();

        switch($ai) {
            case 'google':
                $key = $_ENV['GOOGLE_API_KEY'] ?? null;
                if (!$key) throw new Exception("GOOGLE_API_KEY missing");
                $this->provider = new GoogleProvider($key);
                break;
            case 'groq':
                $key = $_ENV['GROQ_API_KEY'] ?? null;
                if (!$key) throw new Exception("GROQ_API_KEY missing");
                $this->provider = new GroqProvider($key);
                break;
            default: // openai
                $key = $_ENV['OPENAI_API_KEY'] ?? null;
                if (!$key) throw new Exception("OPENAI_API_KEY missing");
                $this->provider = new OpenAIProvider($key);
        }
    }

    public function getSuggestion(array $seoData): string {
        return $this->provider->getSuggestion($seoData);
    }
}
<?php
require_once __DIR__ . '/EnvLoader.php';
require_once __DIR__ . '/providers/GoogleProvider.php';
require_once __DIR__ . '/providers/OpenAIProvider.php';

class AIService {
    private AIProviderInterface $provider;

    public function __construct() {
        EnvLoader::load();
        $ai = EnvLoader::getAIProvider();

        if ($ai === 'google') {
            $key = EnvLoader::getGoogleKey();
            if (!$key) throw new Exception("GOOGLE_API_KEY missing in .env");
            $this->provider = new GoogleProvider($key);
        } else { // openai
            $key = EnvLoader::getOpenAIKey();
            if (!$key) throw new Exception("OPENAI_API_KEY missing in .env");
            $this->provider = new OpenAIProvider($key);
        }
    }

    public function getSuggestion(array $seoData): string {
        return $this->provider->getSuggestion($seoData);
    }
}
<?php
use Dotenv\Dotenv;

class EnvLoader {
    public static function load(): void {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();
    }

    public static function getAIProvider(): string {
        $ai = $_ENV['AI'] ?? 'google';
        return in_array($ai, ['google', 'openai']) ? $ai : 'google';
    }

    public static function getGoogleKey(): ?string {
        return $_ENV['GOOGLE_API_KEY'] ?? null;
    }

    public static function getOpenAIKey(): ?string {
        return $_ENV['OPENAI_API_KEY'] ?? null;
    }
}
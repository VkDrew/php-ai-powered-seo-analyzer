<?php
interface AIProviderInterface {
    public function getSuggestion(array $data): string;
}
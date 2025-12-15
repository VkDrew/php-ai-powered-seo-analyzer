<?php


class Analyzer {
    public static function run(DOMDocument $dom): array {
        // Title
        $title = '';
        $titles = $dom->getElementsByTagName('title');
        if ($titles->length > 0) {
            $title = trim($titles->item(0)->textContent);
        }

        // Meta description
        $metaDesc = '';
        $metas = $dom->getElementsByTagName('meta');
        foreach ($metas as $meta) {
            if (strtolower($meta->getAttribute('name')) === 'description') {
                $metaDesc = trim($meta->getAttribute('content'));
                break;
            }
        }

        // H1 count
        $h1Count = $dom->getElementsByTagName('h1')->length;

        // Body text & keywords
        $body = $dom->getElementsByTagName('body');
        $text = $body->length ? $body->item(0)->textContent : '';
        $words = str_word_count(strtolower($text), 1, '0123456789');
        $wordFreq = array_count_values($words);
        arsort($wordFreq);
        $topKeywords = array_slice(array_filter($wordFreq, fn($w) => strlen($w) > 3), 0, 5, true);

        // Issues
        $issues = [];
        if (strlen($title) < 10 || strlen($title) > 60) {
            $issues[] = "Title is " . strlen($title) . " chars (ideal: 10–60)";
        }
        if (strlen($metaDesc) < 50 || strlen($metaDesc) > 160) {
            $issues[] = "Meta description is " . strlen($metaDesc) . " chars (ideal: 50–160)";
        }
        if ($h1Count === 0) {
            $issues[] = "No <h1> tag found";
        }
        if ($h1Count > 1) {
            $issues[] = "$h1Count <h1> tags found (use only one)";
        }

        // Score (basic)
        $score = 100;
        if (empty($issues)) $score = 100;
        else $score = max(0, 100 - (count($issues) * 25));

        return [
            'title' => $title,
            'meta_description' => $metaDesc,
            'h1_count' => $h1Count,
            'top_keywords' => $topKeywords,
            'issues' => $issues,
            'score' => $score
        ];
    }
}
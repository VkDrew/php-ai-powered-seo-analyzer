<?php


class Crawler {
    /**
     * Fetches HTML content from a URL
     */
    public static function fetch(string $url): string {
        // Validate & normalize URL
        if (!preg_match('~^https?://~i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_USERAGENT      => 'SEO-Analyzer/1.0 (Admin Tool)',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER     => [
                'Accept: text/html,application/xhtml+xml'
            ]
        ]);

        $html = curl_exec($ch);
        $errno = curl_errno($ch);
        $errmsg = curl_error($ch);
        curl_close($ch);

        if ($errno) {
            throw new Exception("Curl error ($errno): $errmsg");
        }

        if (empty($html)) {
            throw new Exception("Empty response from $url");
        }

        return $html;
    }

    /**
     * Parses HTML into DOMDocument
     */
    public static function parse(string $html): DOMDocument {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // Suppress HTML warnings
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        return $dom;
    }
}
<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/EnvLoader.php';
require_once __DIR__ . '/lib/Crawler.php';
require_once __DIR__ . '/lib/Analyzer.php';
require_once __DIR__ . '/lib/AIService.php';

// Validate input
$url = trim($_POST['url'] ?? '');
if (!$url) {
    die('<p style="color:red">❌ URL is required. <a href="index.php">Go back</a></p>');
}

try {
    // 1. Fetch & parse
    $html = Crawler::fetch($url);
    $dom = Crawler::parse($html);

    // 2. Analyze
    $seoData = Analyzer::run($dom);

    // 3. Get AI suggestion
    $ai = new AIService();
    $suggestion = $ai->getSuggestion($seoData);

} catch (Exception $e) {
    die("<p style='color:red'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p><p><a href='index.php'>← Try again</a></p>");
}
?>

<!DOCTYPE html>
<html>
<head><title>SEO Report</title></head>
<body style="font-family:sans-serif; max-width:700px; margin:2rem auto;">
  <h1>✅ SEO Report</h1>
  <p><strong>URL:</strong> <?= htmlspecialchars($url) ?></p>
  <p><strong>Score:</strong> <span style="color:<?= $seoData['score'] >= 80 ? 'green' : ($seoData['score'] >= 50 ? 'orange' : 'red') ?>"><?= $seoData['score'] ?></span>/100</p>

  <h2>🚨 Issues</h2>
  <?php if (empty($seoData['issues'])): ?>
    <p>✅ No major issues found!</p>
  <?php else: ?>
    <ul style="color:#c00">
      <?php foreach ($seoData['issues'] as $issue): ?>
        <li><?= htmlspecialchars($issue) ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <h2>🤖 AI Recommendation</h2>
  <pre style="background:#f8f9fa; padding:1rem; border-radius:4px; overflow:auto;"><?= nl2br(htmlspecialchars($suggestion)) ?></pre>

  <p><a href="index.php">← Analyze another URL</a></p>
</body>
</html>
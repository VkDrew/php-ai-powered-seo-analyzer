<!DOCTYPE html>
<html>
<head>
  <title>SEO Analyzer</title>
  <meta charset="utf-8">
  <style>
    body { font-family: sans-serif; max-width: 600px; margin: 2rem auto; }
    input, button { width: 100%; padding: 10px; margin: 5px 0; }
    button { background: #0d6efd; color: white; border: none; cursor: pointer; }
  </style>
</head>
<body>
  <h1>🔍 SEO Analyzer</h1>
  <form method="POST" action="analyze.php">
    <label>Enter website URL:</label>
    <input type="url" name="url" required placeholder="https://example.com">
    <button type="submit">Analyze</button>
  </form>
</body>
</html>
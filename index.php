<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/EnvLoader.php';
require_once __DIR__ . '/lib/Crawler.php';
require_once __DIR__ . '/lib/Analyzer.php';
require_once __DIR__ . '/lib/AIService.php';

// Increase PHP execution time for AI requests
set_time_limit(120); // 2 minutes
ini_set('max_execution_time', 120);

$seoData = null;
$suggestion = null;
$error = null;
$url = '';

// Process AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    
    $url = trim($_POST['url'] ?? '');
    
    if (empty($url)) {
        echo json_encode(['error' => 'URL is required']);
        exit;
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

        echo json_encode([
            'success' => true,
            'url' => $url,
            'data' => $seoData,
            'suggestion' => $suggestion
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>SEO Analyzer</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body { 
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      padding: 2rem 1rem;
    }
    
    .container {
      max-width: 800px;
      margin: 0 auto;
    }
    
    .card {
      background: white;
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
      margin-bottom: 2rem;
    }
    
    h1 {
      font-size: 2.5rem;
      color: #667eea;
      margin-bottom: 0.5rem;
      text-align: center;
    }
    
    .subtitle {
      text-align: center;
      color: #666;
      margin-bottom: 2rem;
    }
    
    .form-group {
      margin-bottom: 1rem;
    }
    
    label {
      display: block;
      font-weight: 600;
      color: #333;
      margin-bottom: 0.5rem;
    }
    
    input[type="url"] {
      width: 100%;
      padding: 14px 16px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 16px;
      transition: all 0.3s;
    }
    
    input[type="url"]:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .btn {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 18px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      position: relative;
      overflow: hidden;
    }
    
    .btn:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }
    
    .btn:disabled {
      opacity: 0.7;
      cursor: not-allowed;
    }
    
    .btn-text {
      display: inline-block;
      transition: all 0.3s;
    }
    
    .btn.loading .btn-text {
      opacity: 0;
    }
    
    .spinner {
      display: none;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
    
    .btn.loading .spinner {
      display: block;
    }
    
    .spinner::after {
      content: '';
      width: 20px;
      height: 20px;
      border: 3px solid rgba(255,255,255,0.3);
      border-top-color: white;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    
    .result-section {
      display: none;
      animation: fadeIn 0.5s;
    }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .error {
      background: #fee;
      color: #c33;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1rem;
      border-left: 4px solid #c33;
      display: none;
    }
    
    .result-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      flex-wrap: wrap;
      gap: 1rem;
    }
    
    .analyzed-url {
      font-size: 14px;
      color: #666;
      word-break: break-all;
    }
    
    .score-badge {
      font-size: 3rem;
      font-weight: bold;
      padding: 1rem 2rem;
      border-radius: 12px;
      display: inline-block;
    }
    
    .score-high { 
      background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
      color: white;
    }
    
    .score-medium { 
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      color: white;
    }
    
    .score-low { 
      background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
      color: white;
    }
    
    h2 {
      color: #333;
      margin: 2rem 0 1rem 0;
      font-size: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .issues-list {
      list-style: none;
      padding: 0;
    }
    
    .issues-list li {
      background: #fff3cd;
      padding: 0.75rem 1rem;
      margin-bottom: 0.5rem;
      border-radius: 8px;
      border-left: 4px solid #ff9800;
      color: #856404;
    }
    
    .no-issues {
      background: #d4edda;
      color: #155724;
      padding: 1rem;
      border-radius: 8px;
      border-left: 4px solid #28a745;
    }
    
    .ai-box {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      padding: 1.5rem;
      border-radius: 12px;
      white-space: pre-wrap;
      line-height: 1.6;
      color: #333;
      box-shadow: inset 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .analyze-another {
      text-align: center;
      margin-top: 2rem;
    }
    
    .analyze-another a {
      color: #667eea;
      text-decoration: none;
      font-weight: 600;
      font-size: 16px;
      transition: all 0.3s;
    }
    
    .analyze-another a:hover {
      color: #764ba2;
      text-decoration: underline;
    }
    
    .loading-message {
      text-align: center;
      color: #666;
      margin-top: 1rem;
      font-style: italic;
      display: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="card">
      <h1>🔍 SEO Analyzer</h1>
      <p class="subtitle">Analyze your website's SEO with AI-powered insights</p>
      
      <form id="analyzeForm">
        <div class="form-group">
          <label for="url">Enter Website URL:</label>
          <input type="url" id="url" name="url" required placeholder="https://example.com">
        </div>
        
        <button type="submit" class="btn" id="analyzeBtn">
          <span class="btn-text">Analyze Website</span>
          <span class="spinner"></span>
        </button>
        
        <div class="loading-message" id="loadingMsg">
          ⏳ Analyzing website... This may take 30-60 seconds<br>
          <small style="opacity: 0.7;">First request is slower due to model loading. Subsequent requests will be faster.</small>
        </div>
      </form>
      
      <div class="error" id="errorBox"></div>
    </div>

    <div class="card result-section" id="resultSection">
      <div class="result-header">
        <div>
          <h2 style="margin: 0;">✅ SEO Report</h2>
          <p class="analyzed-url" id="analyzedUrl"></p>
        </div>
        <div class="score-badge" id="scoreBadge"></div>
      </div>

      <h2>🚨 Issues Found</h2>
      <div id="issuesContainer"></div>

      <h2>🤖 AI Recommendations</h2>
      <div class="ai-box" id="aiSuggestion"></div>

      <div class="analyze-another">
        <a href="#" id="analyzeAnother">← Analyze Another URL</a>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#analyzeForm').on('submit', function(e) {
        e.preventDefault();
        
        const url = $('#url').val().trim();
        if (!url) return;
        
        // Show loading state
        const $btn = $('#analyzeBtn');
        const $loadingMsg = $('#loadingMsg');
        const $errorBox = $('#errorBox');
        const $resultSection = $('#resultSection');
        
        $btn.prop('disabled', true).addClass('loading');
        $loadingMsg.show();
        $errorBox.hide();
        $resultSection.hide();
        
        // Make AJAX request
        $.ajax({
          url: '',
          method: 'POST',
          data: {
            url: url,
            ajax: '1'
          },
          dataType: 'json',
          timeout: 90000, // Increased to 90 seconds
          success: function(response) {
            if (response.success) {
              displayResults(response);
            } else {
              showError(response.error || 'Unknown error occurred');
            }
          },
          error: function(xhr, status, error) {
            let errorMsg = '';
            
            if (status === 'timeout') {
              errorMsg = '⏱️ Request timeout: The AI model is taking too long to respond. This usually happens on the first request. Please try again in a moment, or consider using Groq API for faster results.';
            } else if (xhr.status === 504) {
              errorMsg = '⏱️ Gateway Timeout: The server took too long to respond. Try again in a moment. Tip: The first request is always slower (model loading).';
            } else if (xhr.status === 502 || xhr.status === 503) {
              errorMsg = '🔧 Service temporarily unavailable. The HuggingFace Space might be starting up. Wait 30 seconds and try again.';
            } else if (xhr.status === 0) {
              errorMsg = '🌐 Network error: Unable to connect. Check your internet connection.';
            } else {
              errorMsg = '❌ Error: ' + (error || 'Request failed. Please try again.');
            }
            
            showError(errorMsg);
          },
          complete: function() {
            $btn.prop('disabled', false).removeClass('loading');
            $loadingMsg.hide();
          }
        });
      });
      
      function displayResults(response) {
        const data = response.data;
        
        // Set URL
        $('#analyzedUrl').text('URL: ' + response.url);
        
        // Set score with color
        const score = data.score;
        const $scoreBadge = $('#scoreBadge');
        $scoreBadge.text(score + '/100');
        
        if (score >= 80) {
          $scoreBadge.removeClass('score-medium score-low').addClass('score-high');
        } else if (score >= 50) {
          $scoreBadge.removeClass('score-high score-low').addClass('score-medium');
        } else {
          $scoreBadge.removeClass('score-high score-medium').addClass('score-low');
        }
        
        // Display issues
        const $issuesContainer = $('#issuesContainer');
        if (data.issues && data.issues.length > 0) {
          let issuesHtml = '<ul class="issues-list">';
          data.issues.forEach(function(issue) {
            issuesHtml += '<li>' + escapeHtml(issue) + '</li>';
          });
          issuesHtml += '</ul>';
          $issuesContainer.html(issuesHtml);
        } else {
          $issuesContainer.html('<div class="no-issues">✅ No major issues found! Your SEO looks great.</div>');
        }
        
        // Display AI suggestion
        $('#aiSuggestion').text(response.suggestion);
        
        // Show results with animation
        $('#resultSection').slideDown(400);
        
        // Smooth scroll to results
        $('html, body').animate({
          scrollTop: $('#resultSection').offset().top - 20
        }, 500);
      }
      
      function showError(message) {
        $('#errorBox').html('<strong>❌ Error:</strong> ' + escapeHtml(message)).slideDown();
      }
      
      function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
      }
      
      $('#analyzeAnother').on('click', function(e) {
        e.preventDefault();
        $('#resultSection').slideUp(400);
        $('#url').val('').focus();
        $('html, body').animate({
          scrollTop: 0
        }, 500);
      });
    });
  </script>
</body>
</html>
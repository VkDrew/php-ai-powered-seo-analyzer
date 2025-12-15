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
  <link rel="stylesheet" href="./assets/style.css">
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
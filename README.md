# 🔍 SEO Analyzer

AI-powered SEO analysis tool with multiple AI provider support. Analyze any website's SEO performance and get actionable AI-generated recommendations.

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&logoColor=white)
![jQuery](https://img.shields.io/badge/jQuery-3.7.1-0769AD?logo=jquery&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green.svg)

---

## ✨ Features

- 🌐 **Website Crawling** - Fetch and analyze any public website
- 🔍 **SEO Analysis** - Check titles, meta descriptions, H1 tags, keywords
- 🤖 **AI-Powered Suggestions** - Get intelligent recommendations from multiple AI providers
- ⚡ **Multiple AI Providers** - Support for Google Gemini, OpenAI, Groq, and HuggingFace
- 🎨 **Modern UI** - Beautiful gradient interface with smooth animations
- 📱 **Responsive Design** - Works on desktop, tablet, and mobile
- ⏱️ **AJAX Loading** - Real-time updates without page refresh
- 🔒 **Flexible Authentication** - Optional API token support

---

## 🚀 Quick Start

### Prerequisites

- PHP 7.4 or higher
- Composer
- cURL extension enabled
- At least one AI provider API key

### Installation

1. **Clone the repository**
```bash
git clone https://github.com/ChamikaSamaraweera/php-ai-powered-seo-analyzer.git
cd php-ai-powered-seo-analyzer
```

2. **Install dependencies**
```bash
composer install
```

3. **Configure environment**
```bash
cp .env.example .env
```

4. **Edit `.env` and add your API key(s)**
```env
# Choose your AI provider
AI=groq

# Add corresponding API key
GROQ_API_KEY=gsk_your_key_here
```

5. **Start PHP server**
```bash
php -S localhost:8000
```

6. **Open browser**
```
http://localhost:8000/index.php
```

---

## 🔑 AI Provider Setup

### Option 1: Groq (Recommended - Fastest!)

**Speed:** 1-2 seconds | **Cost:** Free

1. Get API key: https://console.groq.com/
2. Configure `.env`:
```env
AI=groq
GROQ_API_KEY=gsk_your_key_here
```

### Option 2: Google Gemini

**Speed:** 3-5 seconds | **Cost:** Free tier available

1. Get API key: https://aistudio.google.com/app/apikey
2. Configure `.env`:
```env
AI=google
GOOGLE_API_KEY=your_key_here
```

### Option 3: OpenAI

**Speed:** 2-4 seconds | **Cost:** Paid (cheap)

1. Get API key: https://platform.openai.com/api-keys
2. Configure `.env`:
```env
AI=openai
OPENAI_API_KEY=sk-your_key_here
```

### Option 4: HuggingFace Space (Self-hosted)

**Speed:** 5-60 seconds | **Cost:** Free

1. Deploy your Space: See [HuggingFace Setup](#-huggingface-space-setup)
2. Configure `.env`:
```env
AI=huggingface
HUGGINGFACE_API_URL=https://your-space.hf.space
# Optional token for private spaces
# HUGGINGFACE_API_TOKEN=your_token
```

---

## 📁 Project Structure

```
seo-analyzer/
├── index.php              # Main application (AJAX-powered UI)
├── analyze.php            # Legacy analyzer (can be deleted)
├── composer.json          # PHP dependencies
├── .env.example           # Environment configuration template
├── .gitignore            # Git ignore rules
├── README.md             # This file
│
├── lib/                  # Core libraries
│   ├── AIService.php     # AI provider manager
│   ├── Analyzer.php      # SEO analysis logic
│   ├── Crawler.php       # Website fetching
│   ├── EnvLoader.php     # Environment loader
│   │
│   ├── interfaces/
│   │   └── AIProviderInterface.php
│   │
│   └── providers/        # AI provider implementations
│       ├── GoogleProvider.php
│       ├── OpenAIProvider.php
│       ├── GroqProvider.php
│       └── HuggingFaceProvider.php
│
└── assets/
    └── style.css         # (Empty - styles in index.php)
```

---

## 🎯 Usage

### Basic Analysis

1. Open `http://localhost:8000/index.php`
2. Enter website URL (e.g., `https://example.com`)
3. Click "Analyze Website"
4. Wait for results (1-60 seconds depending on AI provider)
5. Review SEO score, issues, and AI recommendations

### What Gets Analyzed

- ✅ **Page Title** - Length and optimization
- ✅ **Meta Description** - Length and quality
- ✅ **H1 Tags** - Count and structure
- ✅ **Keywords** - Top 5 most frequent words
- ✅ **SEO Score** - 0-100 based on issues found
- ✅ **AI Recommendations** - 3 actionable improvements

---

## ⚙️ Configuration

### Environment Variables

| Variable | Required | Description |
|----------|----------|-------------|
| `AI` | Yes | AI provider: `google`, `openai`, `groq`, or `huggingface` |
| `GOOGLE_API_KEY` | If using Google | Google AI Studio API key |
| `OPENAI_API_KEY` | If using OpenAI | OpenAI API key |
| `GROQ_API_KEY` | If using Groq | Groq API key |
| `HUGGINGFACE_API_URL` | If using HF | Your HuggingFace Space URL |
| `HUGGINGFACE_API_TOKEN` | Optional | Token for private HF Spaces |

### Switching AI Providers

Simply change the `AI` value in `.env`:

```env
# Fast and free (recommended)
AI=groq

# Or use Google Gemini
AI=google

# Or use OpenAI
AI=openai

# Or use your own HuggingFace model
AI=huggingface
```

No code changes needed! Restart PHP server after changes.

---

## 🤖 HuggingFace Space Setup

Want to host your own AI model? Deploy it on HuggingFace Spaces:

### Quick Deploy

1. **Create Space**: https://huggingface.co/new-space
   - SDK: Docker
   - Hardware: CPU basic (free)

2. **Upload Files**:
   - `Dockerfile` (provided in docs)
   - `app.py` (provided in docs)
   - `requirements.txt` (provided in docs)

3. **Optional: Set API Token**
   - Settings → Repository secrets
   - Add `REQUIRE_AUTH=true`
   - Add `API_TOKEN=your-secret-token`

4. **Update `.env`**:
```env
AI=huggingface
HUGGINGFACE_API_URL=https://your-username-seo-analyzer.hf.space
HUGGINGFACE_API_TOKEN=your-secret-token  # if private
```

See full HuggingFace deployment guide in project documentation.

---

## 🎨 Features Showcase

### Modern UI
- 🎨 Beautiful gradient backgrounds
- 🌊 Smooth animations and transitions
- 🎯 Color-coded SEO scores (green/orange/red)
- 📱 Fully responsive design
- ⚡ Real-time AJAX updates

### Loading States
- 🔄 Animated spinner on button
- ⏱️ Progress messages
- ⏰ Timeout handling
- 🚨 Detailed error messages

### Results Display
- 📊 Visual score badges
- 🎯 Organized issue list
- 🤖 AI suggestion box
- 🔗 One-click "Analyze Another" button

---

## 🔧 Customization

### Change AI Model

Edit the provider file in `lib/providers/`:

**Google** (`GoogleProvider.php`):
```php
private string $model = 'gemini-1.5-flash'; // or gemini-pro
```

**OpenAI** (`OpenAIProvider.php`):
```php
private string $model = 'gpt-4o-mini'; // or gpt-4, gpt-3.5-turbo
```

**Groq** (`GroqProvider.php`):
```php
private string $model = 'llama-3.1-8b-instant'; // or mixtral-8x7b
```

### Adjust Timeouts

Edit `lib/providers/HuggingFaceProvider.php`:
```php
CURLOPT_TIMEOUT => 60,  // Increase if needed
```

Edit `index.php` (JavaScript):
```javascript
timeout: 90000,  // 90 seconds
```

### Modify SEO Rules

Edit `lib/Analyzer.php` to customize:
- Title length requirements
- Meta description length
- H1 tag rules
- Scoring algorithm

---

## 🐛 Troubleshooting

### 504 Gateway Timeout

**Problem:** HuggingFace Space takes too long

**Solutions:**
1. Switch to Groq (fastest): Change `AI=groq` in `.env`
2. Increase timeouts (already done in code)
3. Use faster HuggingFace model (see docs)
4. Keep Space warm with UptimeRobot

### API Key Errors

**Problem:** `API_KEY missing` error

**Solution:**
1. Check `.env` file exists
2. Verify correct key name for your provider
3. Ensure no extra spaces in `.env`
4. Restart PHP server after changes

### Empty Results

**Problem:** No data returned from website

**Solution:**
1. Check URL is accessible
2. Verify website allows crawling
3. Check for redirect loops
4. Ensure website returns HTML

### Slow Performance

**Current Provider Speed:**
- HuggingFace: 30-60 seconds ⏰
- Google Gemini: 3-5 seconds
- OpenAI: 2-4 seconds
- **Groq: 1-2 seconds** ⚡ (Recommended!)

**Fix:** Switch to Groq in `.env`:
```env
AI=groq
GROQ_API_KEY=your_key
```

---

## 📊 Performance Comparison

| Provider | Speed | Cost | Quality | Reliability |
|----------|-------|------|---------|-------------|
| **Groq** | ⚡⚡⚡⚡⚡ | FREE | ⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Google Gemini | ⚡⚡⚡⚡ | FREE | ⭐⭐⭐⭐ | ⭐⭐⭐⭐ |
| OpenAI | ⚡⚡⚡⚡ | $ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| HuggingFace | ⚡⚡ | FREE | ⭐⭐⭐ | ⭐⭐⭐ |

**Recommendation:** Start with Groq for best speed/quality balance!

---

## 🔐 Security

- ✅ Input validation and sanitization
- ✅ XSS protection with `htmlspecialchars()`
- ✅ HTTPS enforcement for crawling
- ✅ Environment variable protection (`.gitignore`)
- ✅ Optional API token authentication
- ✅ CSRF protection ready
- ✅ Rate limiting recommended for production

---

## 🚀 Deployment

### Production Checklist

- [ ] Set strong API tokens
- [ ] Enable HTTPS
- [ ] Configure proper timeouts
- [ ] Add rate limiting
- [ ] Set up error logging
- [ ] Configure caching
- [ ] Monitor API usage
- [ ] Set up backups

### Recommended Hosting

- **Shared Hosting**: Any PHP 7.4+ host
- **VPS**: DigitalOcean, Linode, Vultr
- **Cloud**: AWS, Google Cloud, Azure
- **Platform**: Railway.app, Render.com, Fly.io

---

## 🤝 Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

---

## 📝 License

MIT License - feel free to use this project for personal or commercial purposes.

---

## 🙏 Credits

**Built with:**
- PHP & Composer
- jQuery 3.7.1
- Google Gemini API
- OpenAI API
- Groq API
- HuggingFace Transformers

---

## 📞 Support

**Issues?** Open an issue on GitHub

**Questions?** Check the troubleshooting section above

**Want to contribute?** PRs are welcome!

---

## 🎯 Roadmap

- [ ] Add more SEO checks (images, links, speed)
- [ ] Export reports to PDF
- [ ] Historical tracking
- [ ] Competitor analysis
- [ ] Batch URL analysis
- [ ] WordPress plugin
- [ ] Chrome extension
- [ ] API endpoint for integrations

---

## ⚡ Quick Links

- [Get Groq API Key](https://console.groq.com/) (Recommended)
- [Get Google API Key](https://aistudio.google.com/app/apikey)
- [Get OpenAI API Key](https://platform.openai.com/api-keys)
- [Deploy HuggingFace Space](https://huggingface.co/new-space)

---

**Made with ❤️ for better SEO analysis**

Star ⭐ this repo if you find it useful!
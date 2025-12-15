# Contributing to SEO Analyzer

First off, thank you for considering contributing to SEO Analyzer! It's people like you that make this tool better for everyone.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [How Can I Contribute?](#how-can-i-contribute)
- [Development Setup](#development-setup)
- [Pull Request Process](#pull-request-process)
- [Coding Standards](#coding-standards)
- [Adding New AI Providers](#adding-new-ai-providers)
- [Testing Guidelines](#testing-guidelines)
- [Documentation](#documentation)

## Code of Conduct

This project and everyone participating in it is governed by our [Code of Conduct](CODE_OF_CONDUCT.md). By participating, you are expected to uphold this code. Please report unacceptable behavior to the project maintainers.

## How Can I Contribute?

### Reporting Bugs

Before creating bug reports, please check existing issues to avoid duplicates. When you create a bug report, include as many details as possible:

- **Use a clear and descriptive title**
- **Describe the exact steps to reproduce the problem**
- **Provide specific examples** (URLs, error messages, screenshots)
- **Describe the behavior you observed and what you expected**
- **Include your environment details** (PHP version, OS, browser)

### Suggesting Enhancements

Enhancement suggestions are tracked as GitHub issues. When creating an enhancement suggestion:

- **Use a clear and descriptive title**
- **Provide a detailed description** of the proposed functionality
- **Explain why this enhancement would be useful**
- **Include examples** of how it would work
- **List any alternative solutions** you've considered

### Your First Code Contribution

Unsure where to begin? Look for issues tagged with:

- `good first issue` - Simple issues perfect for newcomers
- `help wanted` - Issues that need attention
- `enhancement` - New features to implement
- `bug` - Bugs that need fixing

## Development Setup

### Prerequisites

- PHP 7.4 or higher
- Composer
- Git
- A code editor (VS Code, PHPStorm, etc.)

### Setup Steps

1. **Fork the repository** on GitHub

2. **Clone your fork locally**
   ```bash
   git clone https://github.com/YOUR-USERNAME/php-ai-powered-seo-analyzer.git
   cd php-ai-powered-seo-analyzer
   ```

3. **Add the upstream repository**
   ```bash
   git remote add upstream https://github.com/ChamikaSamaraweera/php-ai-powered-seo-analyzer.git
   ```

4. **Install dependencies**
   ```bash
   composer install
   ```

5. **Configure environment**
   ```bash
   cp .env.example .env
   # Add your API keys for testing
   ```

6. **Start the development server**
   ```bash
   php -S localhost:8000
   ```

### Keep Your Fork Updated

```bash
git fetch upstream
git checkout main
git merge upstream/main
```

## Pull Request Process

1. **Create a new branch** for your feature or bugfix
   ```bash
   git checkout -b feature/amazing-feature
   # or
   git checkout -b fix/bug-description
   ```

2. **Make your changes** following our coding standards

3. **Test your changes thoroughly**
   - Test with different URLs
   - Test with all AI providers (if applicable)
   - Test error handling
   - Test edge cases

4. **Commit your changes**
   ```bash
   git add .
   git commit -m "Add: Brief description of your changes"
   ```
   
   Use conventional commit messages:
   - `Add:` for new features
   - `Fix:` for bug fixes
   - `Update:` for updates to existing features
   - `Remove:` for removing code/features
   - `Docs:` for documentation changes
   - `Style:` for formatting changes
   - `Refactor:` for code refactoring
   - `Test:` for adding tests
   - `Chore:` for maintenance tasks

5. **Push to your fork**
   ```bash
   git push origin feature/amazing-feature
   ```

6. **Create a Pull Request** on GitHub
   - Use a clear title and description
   - Reference any related issues
   - Include screenshots for UI changes
   - List any breaking changes
   - Describe how to test your changes

7. **Respond to feedback** and make requested changes

8. **Wait for approval** from maintainers

### Pull Request Checklist

- [ ] Code follows project style guidelines
- [ ] Code has been tested thoroughly
- [ ] Documentation has been updated
- [ ] Commit messages are clear and descriptive
- [ ] No merge conflicts with main branch
- [ ] All existing tests pass
- [ ] New features include appropriate error handling
- [ ] Security implications have been considered

## Coding Standards

### PHP Standards

- Follow **PSR-12** coding standard
- Use **meaningful variable and function names**
- Add **docblocks** to classes and methods
- Keep functions **small and focused** (single responsibility)
- Use **type declarations** where possible
- Handle **errors gracefully** with try-catch blocks

Example:
```php
<?php

/**
 * Analyzes SEO metrics for a given webpage
 */
class Analyzer {
    /**
     * Runs complete SEO analysis
     * 
     * @param DOMDocument $dom Parsed HTML document
     * @return array SEO analysis results
     */
    public static function run(DOMDocument $dom): array {
        // Implementation
    }
}
```

### JavaScript Standards

- Use **modern ES6+ syntax**
- Use **jQuery** for consistency with existing code
- Add **comments** for complex logic
- Handle **errors gracefully**
- Use **meaningful variable names**

### CSS Standards

- Follow **BEM naming convention** where applicable
- Use **descriptive class names**
- Group related properties together
- Add comments for complex layouts
- Ensure **responsive design** principles

### File Organization

```
lib/
├── interfaces/         # Interface definitions
├── providers/         # AI provider implementations
├── Analyzer.php       # SEO analysis logic
├── Crawler.php        # Web crawling logic
├── AIService.php      # AI service orchestration
└── EnvLoader.php      # Environment management
```

## Adding New AI Providers

Want to add support for a new AI provider? Follow these steps:

1. **Create a new provider class** in `lib/providers/`
   ```php
   <?php
   require_once __DIR__ . '/../interfaces/AIProviderInterface.php';

   class YourProvider implements AIProviderInterface {
       private string $apiKey;
       private string $model = 'your-model-name';

       public function __construct(string $apiKey) {
           $this->apiKey = $apiKey;
       }

       public function getSuggestion(array $data): string {
           // Implementation
       }

       private function buildPrompt(array $d): string {
           // Build prompt from SEO data
       }
   }
   ```

2. **Update `EnvLoader.php`**
   - Add getter method for API key
   - Add provider to `getAIProvider()` validation

3. **Update `AIService.php`**
   - Add case in switch statement
   - Require the new provider file

4. **Update `.env.example`**
   - Add configuration example

5. **Update documentation**
   - Add setup instructions to README
   - Document API endpoints and requirements

6. **Test thoroughly**
   - Test with various inputs
   - Test error handling
   - Test timeout scenarios

## Testing Guidelines

### Manual Testing Checklist

- [ ] Test with valid URLs
- [ ] Test with invalid URLs
- [ ] Test with different website types (blogs, e-commerce, landing pages)
- [ ] Test with slow-loading websites
- [ ] Test with websites that have SEO issues
- [ ] Test with websites that have good SEO
- [ ] Test error handling (network errors, API errors)
- [ ] Test timeout scenarios
- [ ] Test with different AI providers
- [ ] Test UI responsiveness on different screen sizes
- [ ] Test loading states and animations

### Testing Different Scenarios

```bash
# Test with different URLs
http://example.com
https://example.com
example.com
www.example.com

# Test error cases
invalidurl
http://nonexistent-domain-xyz123.com
https://private-site.com (requires auth)
```

## Documentation

### What to Document

- **New features** - How to use them
- **API changes** - Breaking changes, new endpoints
- **Configuration** - New environment variables
- **Code** - Complex functions and algorithms
- **Setup** - New dependencies or requirements

### Documentation Style

- Use **clear, concise language**
- Include **code examples** where helpful
- Add **screenshots** for UI features
- Use **proper Markdown formatting**
- Keep README.md updated with major changes

## Project Structure

```
seo-analyzer/
├── assets/              # Static assets (CSS, images)
├── lib/                 # Core PHP libraries
│   ├── interfaces/      # PHP interfaces
│   └── providers/       # AI provider implementations
├── vendor/              # Composer dependencies (git-ignored)
├── .env.example         # Environment configuration template
├── .gitignore          # Git ignore rules
├── analyze.php         # Legacy analyzer (can be removed)
├── composer.json       # PHP dependencies
├── composer.lock       # Locked dependency versions
├── index.php           # Main application entry point
├── README.md           # Project documentation
├── LICENSE             # MIT License
├── CONTRIBUTING.md     # This file
└── CODE_OF_CONDUCT.md  # Community guidelines
```

## Need Help?

- 📖 Check the [README](README.md) for setup instructions
- 💬 Ask questions in GitHub Discussions
- 🐛 Report bugs via GitHub Issues
- 📧 Contact maintainers for sensitive issues

## Recognition

Contributors will be recognized in:
- Project README contributors section
- Release notes for significant contributions
- Special mentions for major features

## License

By contributing to SEO Analyzer, you agree that your contributions will be licensed under the MIT License.

---

**Thank you for contributing to SEO Analyzer!** 🎉

Your efforts help make this tool better for everyone in the SEO community.
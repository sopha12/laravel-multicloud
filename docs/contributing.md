# 🤝 Contributing Guide

Thank you for your interest in contributing to Laravel MultiCloud! This guide will help you get started.

## 📋 Table of Contents

- [Code of Conduct](contributing.md#code-of-conduct)
- [Getting Started](contributing.md#getting-started)
- [Development Setup](contributing.md#development-setup)
- [Contributing Process](contributing.md#contributing-process)
- [Coding Standards](contributing.md#coding-standards)
- [Testing](contributing.md#testing)
- [Documentation](contributing.md#documentation)
- [Pull Request Process](contributing.md#pull-request-process)

## 📜 Code of Conduct

This project follows the [Contributor Covenant Code of Conduct](CODE_OF_CONDUCT.md). By participating, you agree to uphold this code.

## 🚀 Getting Started

### Prerequisites

- PHP 8.0 or higher
- Composer
- Git
- Laravel 9.x, 10.x, 11.x, or 12.x
- At least one cloud provider account for testing

### Fork and Clone

1. Fork the repository on GitHub
2. Clone your fork locally:

```bash
git clone https://github.com/your-username/laravel-multicloud.git
cd laravel-multicloud
```

3. Add the upstream repository:

```bash
git remote add upstream https://github.com/subhashladumor/laravel-multicloud.git
```

## 🔧 Development Setup

### Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install development dependencies
composer install --dev
```

### Configuration

1. Copy the example configuration:

```bash
cp .env.example .env
```

2. Update configuration with your cloud provider credentials:

```env
# AWS Configuration
AWS_ACCESS_KEY_ID=your-aws-access-key
AWS_SECRET_ACCESS_KEY=your-aws-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name

# Azure Configuration
AZURE_STORAGE_ACCOUNT_NAME=your-account-name
AZURE_STORAGE_ACCOUNT_KEY=your-account-key
AZURE_STORAGE_CONTAINER=your-container-name

# GCP Configuration
GCP_PROJECT_ID=your-project-id
GCP_BUCKET=your-bucket-name
GCP_KEY_FILE=path/to/service-account.json

# Other providers...
```

### Run Tests

```bash
# Run all tests
composer test

# Run with coverage
composer test-coverage

# Run specific test
phpunit tests/CloudTest.php

# Run specific test method
phpunit --filter testUpload tests/CloudTest.php
```

## 🔄 Contributing Process

### 1. Create a Branch

```bash
# Create a new branch for your feature
git checkout -b feature/your-feature-name

# Or for bug fixes
git checkout -b fix/your-bug-fix
```

### 2. Make Changes

- Write your code following the coding standards
- Add tests for new functionality
- Update documentation if needed
- Ensure all tests pass

### 3. Test Your Changes

```bash
# Run tests
composer test

# Check code style
composer check-style

# Run static analysis
composer analyse
```

### 4. Commit Changes

```bash
# Add your changes
git add .

# Commit with descriptive message
git commit -m "Add new feature: description of what you added"
```

### 5. Push and Create Pull Request

```bash
# Push to your fork
git push origin feature/your-feature-name

# Create pull request on GitHub
```

## 📝 Coding Standards

### PHP Standards

- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standard
- Use PHP 8.0+ features where appropriate
- Write type declarations for all methods
- Use meaningful variable and method names
- Add PHPDoc comments for all public methods

### Example Code Style

```php
<?php

declare(strict_types=1);

namespace Subhashladumor\LaravelMulticloud\Providers;

use Subhashladumor\LaravelMulticloud\Contracts\CloudProviderInterface;

/**
 * Example Provider
 * 
 * @package Subhashladumor\LaravelMulticloud\Providers
 * @author Your Name <your.email@example.com>
 * @license MIT
 */
class ExampleProvider implements CloudProviderInterface
{
    /**
     * Example method
     * 
     * @param string $path File path
     * @param mixed $file File content
     * @param array $options Additional options
     * @return array Result array
     */
    public function upload(string $path, $file, array $options = []): array
    {
        // Implementation here
        return [
            'status' => 'success',
            'path' => $path,
            'provider' => 'example',
        ];
    }
}
```

### Laravel Standards

- Follow Laravel conventions
- Use Laravel's built-in features where possible
- Use dependency injection
- Follow Laravel's naming conventions

### Git Commit Messages

Use clear, descriptive commit messages:

```bash
# Good examples
git commit -m "Add support for new cloud provider"
git commit -m "Fix file upload validation issue"
git commit -m "Update documentation for API endpoints"

# Bad examples
git commit -m "fix"
git commit -m "update"
git commit -m "changes"
```

## 🧪 Testing

### Test Requirements

- All new features must have tests
- All bug fixes must have tests
- Tests must pass before submitting PR
- Aim for high test coverage

### Writing Tests

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use Subhashladumor\LaravelMulticloud\Providers\ExampleProvider;

class ExampleProviderTest extends TestCase
{
    private ExampleProvider $provider;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->provider = new ExampleProvider();
        $this->provider->connect([
            'key' => 'test-key',
            'secret' => 'test-secret',
        ]);
    }
    
    public function testUpload(): void
    {
        $result = $this->provider->upload('test/file.txt', 'Hello World!');
        
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('example', $result['provider']);
    }
    
    public function testUploadWithOptions(): void
    {
        $result = $this->provider->upload('test/file.txt', 'Hello World!', [
            'option1' => 'value1',
        ]);
        
        $this->assertEquals('success', $result['status']);
    }
}
```

### Test Categories

- **Unit Tests**: Test individual classes and methods
- **Integration Tests**: Test interactions between components
- **Feature Tests**: Test complete user workflows

## 📚 Documentation

### Documentation Requirements

- Update README.md if adding new features
- Add examples for new functionality
- Update API documentation
- Include usage examples

### Documentation Style

- Use clear, concise language
- Include code examples
- Use proper markdown formatting
- Include screenshots for UI changes

### Example Documentation

```markdown
## New Feature

### Description
Brief description of the new feature.

### Usage
```php
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

$result = LaravelMulticloud::newFeature('parameter');
```

### Options
| Option | Type | Description |
|--------|------|-------------|
| `option1` | string | Description of option1 |
| `option2` | array | Description of option2 |

### Examples
```php
// Basic usage
$result = LaravelMulticloud::newFeature('value');

// With options
$result = LaravelMulticloud::newFeature('value', [
    'option1' => 'value1',
    'option2' => ['value2', 'value3'],
]);
```
```

## 🔄 Pull Request Process

### Before Submitting

1. **Ensure tests pass**
   ```bash
   composer test
   ```

2. **Check code style**
   ```bash
   composer check-style
   ```

3. **Update documentation**
   - Update README.md if needed
   - Add examples for new features
   - Update API documentation

4. **Squash commits** if necessary
   ```bash
   git rebase -i HEAD~n  # where n is number of commits
   ```

### Pull Request Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
- [ ] Tests pass
- [ ] New tests added
- [ ] Manual testing completed

## Checklist
- [ ] Code follows style guidelines
- [ ] Self-review completed
- [ ] Documentation updated
- [ ] Tests added/updated
```

### Review Process

1. **Automated checks** must pass
2. **Code review** by maintainers
3. **Testing** by maintainers
4. **Approval** and merge

## 🐛 Reporting Bugs

### Bug Report Template

```markdown
## Bug Description
Clear description of the bug

## Steps to Reproduce
1. Step 1
2. Step 2
3. Step 3

## Expected Behavior
What should happen

## Actual Behavior
What actually happens

## Environment
- Laravel Version: 10.x
- PHP Version: 8.1
- Package Version: 1.0.0
- OS: Ubuntu 20.04

## Additional Context
Any additional information
```

### Bug Report Checklist

- [ ] Bug is reproducible
- [ ] Steps to reproduce are clear
- [ ] Expected vs actual behavior is described
- [ ] Environment information is provided
- [ ] No duplicate issues exist

## 💡 Feature Requests

### Feature Request Template

```markdown
## Feature Description
Clear description of the requested feature

## Use Case
Why is this feature needed?

## Proposed Solution
How should this feature work?

## Alternatives Considered
Other solutions you've considered

## Additional Context
Any additional information
```

### Feature Request Checklist

- [ ] Feature is clearly described
- [ ] Use case is explained
- [ ] Proposed solution is detailed
- [ ] No duplicate requests exist
- [ ] Feature aligns with project goals

## 🏷️ Release Process

### Version Numbering

We follow [Semantic Versioning](https://semver.org/):

- **MAJOR**: Breaking changes
- **MINOR**: New features (backward compatible)
- **PATCH**: Bug fixes (backward compatible)

### Release Checklist

- [ ] All tests pass
- [ ] Documentation updated
- [ ] Changelog updated
- [ ] Version bumped
- [ ] Tag created
- [ ] Release notes written

## 📞 Getting Help

### Communication Channels

- **GitHub Issues**: For bugs and feature requests
- **GitHub Discussions**: For questions and discussions
- **Email**: subhashladumor@gmail.com

### Response Time

- **Issues**: Within 48 hours
- **Pull Requests**: Within 72 hours
- **Discussions**: Within 24 hours

## 🙏 Recognition

Contributors will be recognized in:

- **README.md**: Contributor list
- **CHANGELOG.md**: Contribution credits
- **Release Notes**: Feature acknowledgments

## 📄 License

By contributing, you agree that your contributions will be licensed under the MIT License.

## 📚 Additional Resources

1. **Check [Examples & Tutorials](examples.md)** for practical usage
2. **Explore [Cloud Providers](cloud-providers.md)** for provider-specific features
3. **Read [API Reference](api-reference.md)** for complete method documentation
4. **Learn about [Testing](testing.md)** for testing your implementation

---

**Ready to contribute?** Check out [Examples & Tutorials](examples.md) for practical usage patterns!

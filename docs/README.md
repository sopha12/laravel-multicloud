# 📚 Laravel MultiCloud Documentation

Welcome to the comprehensive documentation for Laravel MultiCloud - the unified package for managing multiple cloud providers in Laravel applications.

## 📖 Table of Contents

- [Installation Guide](installation.md)
- [Configuration](configuration.md)
- [API Reference](api-reference.md)
- [Cloud Providers](cloud-providers.md)
- [Examples & Tutorials](examples.md)
- [Artisan Commands](artisan-commands.md)
- [HTTP API](http-api.md)
- [Testing](testing.md)
- [Troubleshooting](troubleshooting.md)
- [Contributing](contributing.md)
- [Changelog](changelog.md)

## 🚀 Quick Start

### Installation

```bash
composer require subhashladumor/laravel-multicloud
```

### Basic Usage

```php
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

// Upload a file
$result = LaravelMulticloud::upload('images/photo.jpg', $fileContent);

// Download a file
$content = LaravelMulticloud::download('images/photo.jpg');

// Generate signed URL
$url = LaravelMulticloud::generateSignedUrl('images/photo.jpg', 3600);
```

## 🌟 Key Features

- **9 Cloud Providers**: AWS, Azure, GCP, Cloudinary, Alibaba, IBM, DigitalOcean, Oracle, Cloudflare
- **Unified API**: Single interface for all cloud operations
- **Type Safety**: Full PHP 8.0+ type declarations
- **Laravel Integration**: Facade, Service Provider, Artisan Commands
- **HTTP API**: RESTful endpoints for web applications
- **Comprehensive Testing**: Full test coverage included

## 📋 Supported Operations

| Operation | Description | All Providers |
|-----------|-------------|---------------|
| `upload()` | Upload files to cloud storage | ✅ |
| `download()` | Download files from cloud storage | ✅ |
| `delete()` | Delete files from cloud storage | ✅ |
| `list()` | List files in cloud storage | ✅ |
| `exists()` | Check if file exists | ✅ |
| `getMetadata()` | Get file metadata | ✅ |
| `generateSignedUrl()` | Generate temporary access URLs | ✅ |
| `getUsage()` | Get usage statistics | ✅ |
| `testConnection()` | Test provider connection | ✅ |

## 🔧 Supported Cloud Providers

| Provider | Status | Special Features |
|----------|--------|------------------|
| **AWS S3** | ✅ | ACL, Lifecycle Policies |
| **Microsoft Azure** | ✅ | Blob Storage, Containers |
| **Google Cloud Platform** | ✅ | Bucket Operations, IAM |
| **Cloudinary** | ✅ | Image/Video Transformations |
| **Alibaba Cloud** | ✅ | OSS, CDN Integration |
| **IBM Cloud** | ✅ | COS, Object Storage |
| **DigitalOcean** | ✅ | Spaces, CDN |
| **Oracle Cloud** | ✅ | OCI Object Storage |
| **Cloudflare** | ✅ | R2, Custom Domains |

## 📊 Usage Statistics

Track your cloud usage across all providers:

```php
// Get usage for default provider
$usage = LaravelMulticloud::getUsage();

// Get usage for specific provider
$usage = LaravelMulticloud::driver('aws')->getUsage();

// Get usage for all providers
$allUsage = [];
foreach (LaravelMulticloud::getAvailableDrivers() as $provider => $name) {
    $allUsage[$provider] = LaravelMulticloud::driver($provider)->getUsage();
}
```

## 🎯 Artisan Commands

```bash
# Deploy to cloud
php artisan cloud:deploy --provider=aws

# Check usage statistics
php artisan cloud:usage --provider=gcp --detailed

# Test all providers
php artisan cloud:usage --all
```

## 🌐 HTTP API

Access cloud operations via RESTful API:

```bash
# Upload file
POST /api/multicloud/upload

# Download file
GET /api/multicloud/download

# Generate signed URL
GET /api/multicloud/signed-url
```

## 🧪 Testing

```bash
# Run tests
composer test

# Run with coverage
composer test-coverage
```

## 📞 Support

- **Issues**: [GitHub Issues](https://github.com/subhashladumor/laravel-multicloud/issues)
- **Discussions**: [GitHub Discussions](https://github.com/subhashladumor/laravel-multicloud/discussions)
- **Email**: subhashladumor@gmail.com

## 📄 License

This package is open-sourced software licensed under the [MIT license](LICENSE).

---

**Next Steps**: Check out the [Installation Guide](installation.md) to get started!

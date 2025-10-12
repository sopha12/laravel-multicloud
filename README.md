# 🚀 Laravel MultiCloud

[![Latest Version](https://img.shields.io/packagist/v/subhashladumor/laravel-multicloud.svg?style=flat-square)](https://packagist.org/packages/subhashladumor/laravel-multicloud)
[![Total Downloads](https://img.shields.io/packagist/dt/subhashladumor/laravel-multicloud.svg?style=flat-square)](https://packagist.org/packages/subhashladumor/laravel-multicloud)
[![License](https://img.shields.io/packagist/l/subhashladumor/laravel-multicloud.svg?style=flat-square)](https://packagist.org/packages/subhashladumor/laravel-multicloud)
[![PHP Version](https://img.shields.io/packagist/php-v/subhashladumor/laravel-multicloud.svg?style=flat-square)](https://packagist.org/packages/subhashladumor/laravel-multicloud)
[![Laravel Version](https://img.shields.io/badge/Laravel-9.x%20%7C%2010.x%20%7C%2011.x-red.svg?style=flat-square)](https://laravel.com)

> **Unified Laravel package to manage and integrate multiple cloud providers (AWS, Azure, GCP, Cloudinary, Alibaba Cloud, IBM Cloud, DigitalOcean, Oracle Cloud, Cloudflare) using a single, consistent API layer.**

## 🌟 Features

- **🔄 Multi-Provider Support**: Seamlessly work with 9 major cloud providers
- **🎯 Unified API**: Single interface for all cloud operations
- **⚡ Easy Integration**: Simple Laravel facade and service provider
- **🛡️ Type Safety**: Full PHP 8.1+ type declarations
- **📊 Usage Analytics**: Built-in usage tracking and cost monitoring
- **🔧 Artisan Commands**: Deploy and monitor via command line
- **🌐 HTTP API**: RESTful endpoints for web applications
- **🧪 Tested**: Comprehensive test suite included
- **📚 Well Documented**: Extensive documentation and examples

## 🏗️ Supported Cloud Providers

| Provider | Status | Features |
|----------|--------|----------|
| **AWS S3** | ✅ | Upload, Download, Delete, List, Signed URLs |
| **Microsoft Azure** | ✅ | Blob Storage, Container Management |
| **Google Cloud Platform** | ✅ | Cloud Storage, Bucket Operations |
| **Cloudinary** | ✅ | Image/Video Management, Transformations |
| **Alibaba Cloud** | ✅ | Object Storage Service (OSS) |
| **IBM Cloud** | ✅ | Object Storage, COS Integration |
| **DigitalOcean** | ✅ | Spaces, CDN Integration |
| **Oracle Cloud** | ✅ | Object Storage, OCI Integration |
| **Cloudflare** | ✅ | R2 Storage, Custom Domains |

## 📦 Installation

### Via Composer

```bash
composer require subhashladumor/laravel-multicloud
```

### Publish Configuration

```bash
php artisan vendor:publish --provider="Subhashladumor\LaravelMulticloud\LaravelMulticloudServiceProvider" --tag="multicloud-config"
```

### Environment Variables

Add your cloud provider credentials to your `.env` file:

```env
# Default Provider
MULTICLOUD_DEFAULT=aws

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

# Cloudinary Configuration
CLOUDINARY_CLOUD_NAME=your-cloud-name
CLOUDINARY_API_KEY=your-api-key
CLOUDINARY_API_SECRET=your-api-secret

# Alibaba Cloud Configuration
ALIBABA_ACCESS_KEY_ID=your-access-key-id
ALIBABA_ACCESS_KEY_SECRET=your-access-key-secret
ALIBABA_OSS_ENDPOINT=your-endpoint
ALIBABA_OSS_BUCKET=your-bucket-name

# IBM Cloud Configuration
IBM_API_KEY=your-api-key
IBM_SERVICE_INSTANCE_ID=your-service-instance-id
IBM_ENDPOINT=your-endpoint
IBM_BUCKET=your-bucket-name

# DigitalOcean Configuration
DO_SPACES_ACCESS_KEY=your-access-key
DO_SPACES_SECRET_KEY=your-secret-key
DO_SPACES_REGION=nyc3
DO_SPACES_BUCKET=your-bucket-name

# Oracle Cloud Configuration
ORACLE_USER_OCID=your-user-ocid
ORACLE_TENANCY_OCID=your-tenancy-ocid
ORACLE_FINGERPRINT=your-fingerprint
ORACLE_PRIVATE_KEY=your-private-key
ORACLE_REGION=us-ashburn-1
ORACLE_BUCKET=your-bucket-name
ORACLE_NAMESPACE=your-namespace

# Cloudflare Configuration
CLOUDFLARE_ACCOUNT_ID=your-account-id
CLOUDFLARE_ACCESS_KEY_ID=your-access-key-id
CLOUDFLARE_SECRET_ACCESS_KEY=your-secret-access-key
CLOUDFLARE_BUCKET=your-bucket-name
CLOUDFLARE_CUSTOM_DOMAIN=your-custom-domain.com
```

## 🚀 Quick Start

### Basic Usage

```php
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

// Upload a file using default provider
$result = LaravelMulticloud::upload('images/photo.jpg', $fileContent);

// Upload to specific provider
$result = LaravelMulticloud::driver('gcp')->upload('documents/file.pdf', $fileContent);

// Download a file
$content = LaravelMulticloud::download('images/photo.jpg');

// Delete a file
$result = LaravelMulticloud::delete('images/photo.jpg');

// List files
$files = LaravelMulticloud::list('images/');

// Check if file exists
$exists = LaravelMulticloud::exists('images/photo.jpg');

// Get file metadata
$metadata = LaravelMulticloud::getMetadata('images/photo.jpg');

// Generate signed URL
$signedUrl = LaravelMulticloud::generateSignedUrl('images/photo.jpg', 3600);

// Get usage statistics
$usage = LaravelMulticloud::getUsage();

// Test connection
$connection = LaravelMulticloud::testConnection();
```

### Advanced Usage

```php
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

// Upload with options
$result = LaravelMulticloud::driver('aws')->upload('images/photo.jpg', $fileContent, [
    'ACL' => 'public-read',
    'CacheControl' => 'max-age=31536000',
    'ContentType' => 'image/jpeg',
]);

// List files with options
$files = LaravelMulticloud::driver('azure')->list('documents/', [
    'prefix' => '2024/',
    'max_keys' => 100,
]);

// Download to local path
$result = LaravelMulticloud::driver('gcp')->download('backup/data.zip', '/tmp/data.zip');

// Get all available providers
$providers = LaravelMulticloud::getAvailableDrivers();
```

## 🎯 Artisan Commands

### Deploy Command

```bash
# Deploy to default provider
php artisan cloud:deploy

# Deploy to specific provider
php artisan cloud:deploy --provider=aws

# Deploy to specific environment and region
php artisan cloud:deploy --provider=gcp --environment=staging --region=us-central1

# Dry run deployment
php artisan cloud:deploy --provider=azure --dry-run
```

### Usage Command

```bash
# Show usage for default provider
php artisan cloud:usage

# Show usage for specific provider
php artisan cloud:usage --provider=aws

# Show detailed usage information
php artisan cloud:usage --provider=gcp --detailed

# Show usage for all providers
php artisan cloud:usage --all

# Export usage data
php artisan cloud:usage --provider=azure --format=json
php artisan cloud:usage --all --format=csv
```

## 🌐 HTTP API Endpoints

The package provides RESTful API endpoints for web applications:

```bash
# File Operations
POST   /api/multicloud/upload          # Upload file
GET    /api/multicloud/download       # Download file
DELETE /api/multicloud/delete          # Delete file
GET    /api/multicloud/list           # List files
GET    /api/multicloud/exists         # Check file exists
GET    /api/multicloud/metadata       # Get file metadata
GET    /api/multicloud/signed-url     # Generate signed URL

# Provider Operations
GET    /api/multicloud/usage          # Get usage statistics
GET    /api/multicloud/test-connection # Test provider connection
GET    /api/multicloud/providers      # List available providers
```

### API Examples

```bash
# Upload file
curl -X POST http://your-app.com/api/multicloud/upload \
  -F "file=@photo.jpg" \
  -F "path=images/photo.jpg" \
  -F "provider=aws"

# Download file
curl -X GET "http://your-app.com/api/multicloud/download?path=images/photo.jpg&provider=aws"

# Generate signed URL
curl -X GET "http://your-app.com/api/multicloud/signed-url?path=images/photo.jpg&expiration=3600"

# Get usage statistics
curl -X GET "http://your-app.com/api/multicloud/usage?provider=aws"
```

## 🔧 Configuration

### Provider Configuration

```php
// config/multicloud.php
return [
    'default' => 'aws',
    
    'providers' => [
        'aws' => [
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'bucket' => env('AWS_BUCKET'),
            'options' => [
                'ACL' => 'private',
                'CacheControl' => 'max-age=31536000',
            ],
        ],
        // ... other providers
    ],
    
    'settings' => [
        'upload' => [
            'max_file_size' => 10485760, // 10MB
            'allowed_extensions' => ['jpg', 'png', 'pdf', 'doc'],
        ],
        'cache' => [
            'enabled' => true,
            'ttl' => 3600,
        ],
    ],
];
```

### Fallback Configuration

```php
'fallback' => [
    'enabled' => true,
    'providers' => [
        'aws' => ['azure', 'gcp'],
        'azure' => ['aws', 'gcp'],
        'gcp' => ['aws', 'azure'],
    ],
    'max_retries' => 3,
    'retry_delay' => 1000, // milliseconds
],
```

## 🧪 Testing

Run the test suite:

```bash
# Run all tests
composer test

# Run with coverage
composer test-coverage

# Run specific test
phpunit tests/CloudTest.php
```

## 📊 Usage Examples

### File Upload with Progress

```php
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

// Upload large file with progress tracking
$file = request()->file('large_file');
$path = 'uploads/' . $file->getClientOriginalName();

$result = LaravelMulticloud::driver('aws')->upload($path, $file->getContent(), [
    'ACL' => 'public-read',
    'ContentType' => $file->getMimeType(),
]);

if ($result['status'] === 'success') {
    return response()->json([
        'message' => 'File uploaded successfully',
        'url' => $result['url'],
        'size' => $result['size'],
    ]);
}
```

### Batch Operations

```php
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

// Upload multiple files
$files = request()->file('files');
$results = [];

foreach ($files as $file) {
    $path = 'uploads/' . time() . '_' . $file->getClientOriginalName();
    $result = LaravelMulticloud::upload($path, $file->getContent());
    $results[] = $result;
}

return response()->json(['uploads' => $results]);
```

### Image Processing with Cloudinary

```php
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

// Upload image with transformations
$result = LaravelMulticloud::driver('cloudinary')->upload('images/photo.jpg', $imageContent, [
    'transformation' => [
        'width' => 800,
        'height' => 600,
        'crop' => 'fill',
        'quality' => 'auto',
        'format' => 'auto',
    ],
]);

// Generate different image sizes
$thumbnailUrl = LaravelMulticloud::driver('cloudinary')->generateSignedUrl('images/photo.jpg', 3600);
```

### Backup Operations

```php
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

// Create database backup
$backupFile = 'backups/db_backup_' . date('Y-m-d_H-i-s') . '.sql';
$backupContent = shell_exec('mysqldump --all-databases');

$result = LaravelMulticloud::driver('gcp')->upload($backupFile, $backupContent, [
    'ContentType' => 'application/sql',
    'CacheControl' => 'no-cache',
]);

// Schedule cleanup of old backups
$oldBackups = LaravelMulticloud::driver('gcp')->list('backups/');
foreach ($oldBackups['files'] as $file) {
    if (strtotime($file['last_modified']) < strtotime('-30 days')) {
        LaravelMulticloud::driver('gcp')->delete($file['path']);
    }
}
```

## 🔒 Security Features

- **Encrypted Uploads**: Optional encryption for sensitive files
- **Signed URLs**: Secure temporary access to files
- **Access Control**: Configurable ACL and permissions
- **Input Validation**: Comprehensive request validation
- **Rate Limiting**: Built-in rate limiting for API endpoints

## 🚀 Performance Optimization

- **Caching**: Built-in caching for metadata and usage stats
- **Parallel Operations**: Support for concurrent uploads/downloads
- **CDN Integration**: Automatic CDN configuration
- **Compression**: Automatic file compression for supported formats
- **Lazy Loading**: On-demand provider initialization

## 📈 Monitoring & Analytics

- **Usage Tracking**: Detailed usage statistics for each provider
- **Cost Monitoring**: Real-time cost tracking and alerts
- **Performance Metrics**: Upload/download speed monitoring
- **Error Tracking**: Comprehensive error logging and reporting
- **Health Checks**: Automated provider health monitoring

## 🤝 Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

### Development Setup

```bash
# Clone the repository
git clone https://github.com/subhashladumor/laravel-multicloud.git

# Install dependencies
composer install

# Run tests
composer test

# Run code quality checks
composer check
```

## 📄 License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## 🆘 Support

- **Documentation**: [Full Documentation](https://github.com/subhashladumor/laravel-multicloud/wiki)
- **Issues**: [GitHub Issues](https://github.com/subhashladumor/laravel-multicloud/issues)
- **Discussions**: [GitHub Discussions](https://github.com/subhashladumor/laravel-multicloud/discussions)
- **Email**: subhashladumor@gmail.com

## 🙏 Acknowledgments

- Laravel Framework Team
- All Cloud Provider SDKs
- Open Source Community
- Contributors and Testers

## 📊 SEO Keywords

**Primary Keywords**: laravel cloud, laravel multicloud, laravel aws, laravel azure, laravel gcp, laravel cloud manager, laravel s3, laravel cloudflare, laravel devops, laravel automation, laravel cloud sdk, laravel backup deploy, laravel cloud api, laravel cloud storage, laravel multi cloud integration, cloud management for laravel

**Secondary Keywords**: php cloud storage, laravel file upload, laravel cloudinary, laravel digitalocean, laravel oracle cloud, laravel ibm cloud, laravel alibaba cloud, laravel cloud integration, laravel storage driver, laravel cloud provider, laravel cloud service, laravel cloud platform, laravel cloud solution, laravel cloud toolkit, laravel cloud utilities

---

<div align="center">

**Made with ❤️ by [Subhash Ladumor](https://github.com/subhashladumor)**

[⭐ Star this repo](https://github.com/subhashladumor/laravel-multicloud) | [🐛 Report Bug](https://github.com/subhashladumor/laravel-multicloud/issues) | [💡 Request Feature](https://github.com/subhashladumor/laravel-multicloud/issues)

</div>
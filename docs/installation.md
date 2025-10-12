# 🚀 Installation Guide

This guide will walk you through installing and setting up Laravel MultiCloud in your Laravel application.

## 📋 Requirements

- **PHP**: >= 8.0
- **Laravel**: 9.x, 10.x, 11.x, or 12.x
- **Composer**: Latest version
- **Cloud Provider Accounts**: At least one cloud provider account

## 📦 Installation

### Step 1: Install via Composer

```bash
composer require subhashladumor/laravel-multicloud
```

### Step 2: Publish Configuration

```bash
php artisan vendor:publish --provider="Subhashladumor\LaravelMulticloud\LaravelMulticloudServiceProvider" --tag="multicloud-config"
```

This will create a `config/multicloud.php` file in your Laravel application.

### Step 3: Configure Environment Variables

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

## 🔧 Configuration

### Basic Configuration

The package will automatically register itself with Laravel. You can start using it immediately:

```php
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

// Upload a file
$result = LaravelMulticloud::upload('test/file.txt', 'Hello World!');
```

### Advanced Configuration

Edit `config/multicloud.php` to customize the package behavior:

```php
return [
    'default' => env('MULTICLOUD_DEFAULT', 'aws'),
    
    'providers' => [
        'aws' => [
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'bucket' => env('AWS_BUCKET'),
            'options' => [
                'ACL' => env('AWS_ACL', 'private'),
                'CacheControl' => env('AWS_CACHE_CONTROL', 'max-age=31536000'),
            ],
        ],
        // ... other providers
    ],
    
    'settings' => [
        'upload' => [
            'max_file_size' => env('MULTICLOUD_MAX_FILE_SIZE', 10485760), // 10MB
            'allowed_extensions' => [
                'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp',
                'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
                'txt', 'csv', 'json', 'xml', 'zip', 'rar',
                'mp4', 'avi', 'mov', 'wmv', 'flv', 'webm',
                'mp3', 'wav', 'flac', 'aac', 'ogg',
            ],
        ],
    ],
];
```

## 🧪 Testing Installation

### Test Connection

```bash
php artisan cloud:usage --provider=aws
```

### Test Upload

```php
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

// Test upload
$result = LaravelMulticloud::upload('test/hello.txt', 'Hello World!');

if ($result['status'] === 'success') {
    echo "Installation successful! File uploaded to: " . $result['url'];
} else {
    echo "Installation failed: " . $result['message'];
}
```

## 🔍 Troubleshooting

### Common Issues

#### 1. Service Provider Not Found

**Error**: `Class 'Subhashladumor\LaravelMulticloud\LaravelMulticloudServiceProvider' not found`

**Solution**: Run `composer dump-autoload` and ensure the package is properly installed.

#### 2. Configuration Not Published

**Error**: `Configuration file not found`

**Solution**: Run the publish command:
```bash
php artisan vendor:publish --provider="Subhashladumor\LaravelMulticloud\LaravelMulticloudServiceProvider" --tag="multicloud-config"
```

#### 3. Credentials Not Working

**Error**: `Connection failed` or `Authentication failed`

**Solution**: 
- Verify your credentials in `.env`
- Check if the cloud provider account is active
- Ensure the bucket/container exists
- Verify permissions for the API keys

#### 4. Facade Not Working

**Error**: `Class 'LaravelMulticloud' not found`

**Solution**: Ensure the facade is properly registered in `config/app.php`:
```php
'aliases' => [
    // ... other aliases
    'LaravelMulticloud' => Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud::class,
],
```

### Debug Mode

Enable debug mode to see detailed error messages:

```php
// In config/multicloud.php
'logging' => [
    'enabled' => true,
    'level' => 'debug',
],
```

## 📚 Next Steps

1. **Read the [Configuration Guide](configuration.md)** for detailed setup options
2. **Check out [Examples & Tutorials](examples.md)** for practical usage
3. **Explore the [API Reference](api-reference.md)** for complete method documentation
4. **Learn about [Cloud Providers](cloud-providers.md)** for provider-specific features

## 🆘 Getting Help

If you encounter any issues during installation:

1. Check the [Troubleshooting Guide](troubleshooting.md)
2. Search [GitHub Issues](https://github.com/subhashladumor/laravel-multicloud/issues)
3. Create a new issue with detailed error information
4. Contact support at subhashladumor@gmail.com

---

**Ready to start?** Check out the [Configuration Guide](configuration.md) next!

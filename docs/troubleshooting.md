# 🔧 Troubleshooting Guide

Common issues and solutions for Laravel MultiCloud.

## 🚨 Common Issues

### Installation Issues

#### Package Not Found

**Error:** `Package subhashladumor/laravel-multicloud not found`

**Solution:**
```bash
# Clear Composer cache
composer clear-cache

# Update Composer
composer self-update

# Try installing again
composer require subhashladumor/laravel-multicloud
```

#### Service Provider Not Found

**Error:** `Class 'Subhashladumor\LaravelMulticloud\LaravelMulticloudServiceProvider' not found`

**Solution:**
```bash
# Regenerate autoload files
composer dump-autoload

# Clear application cache
php artisan config:clear
php artisan cache:clear
```

#### Configuration Not Published

**Error:** `Configuration file not found`

**Solution:**
```bash
# Publish configuration
php artisan vendor:publish --provider="Subhashladumor\LaravelMulticloud\LaravelMulticloudServiceProvider" --tag="multicloud-config"

# Verify configuration exists
ls config/multicloud.php
```

### Configuration Issues

#### Invalid Provider

**Error:** `Invalid provider: invalid-provider`

**Solution:**
```php
// Check available providers
$providers = LaravelMulticloud::getAvailableDrivers();
dd($providers);

// Use valid provider name
LaravelMulticloud::driver('aws'); // ✅ Correct
LaravelMulticloud::driver('invalid-provider'); // ❌ Wrong
```

#### Missing Credentials

**Error:** `Connection failed: Invalid credentials`

**Solution:**
```bash
# Check environment variables
php artisan config:show multicloud.providers.aws

# Verify .env file
cat .env | grep AWS

# Test connection
php artisan cloud:usage --provider=aws
```

#### Provider Not Enabled

**Error:** `Provider not enabled`

**Solution:**
```php
// Check enabled providers
$enabled = config('multicloud.enabled_providers');
dd($enabled);

// Enable provider in config
'enabled_providers' => [
    'aws' => true, // Enable AWS
    'azure' => true, // Enable Azure
    // ...
],
```

### Connection Issues

#### Network Timeout

**Error:** `Connection timeout`

**Solution:**
```php
// Increase timeout in config
'providers' => [
    'aws' => [
        'timeout' => 60, // Increase timeout
        'connect_timeout' => 30,
    ],
],
```

#### SSL Certificate Issues

**Error:** `SSL certificate verification failed`

**Solution:**
```php
// Disable SSL verification (development only)
'providers' => [
    'aws' => [
        'verify' => false, // Disable SSL verification
    ],
],
```

#### Rate Limiting

**Error:** `Rate limit exceeded`

**Solution:**
```php
// Implement rate limiting
use Illuminate\Support\Facades\RateLimiter;

if (RateLimiter::tooManyAttempts('cloud-upload', 10)) {
    throw new \Exception('Too many upload attempts');
}

RateLimiter::hit('cloud-upload', 60); // 1 minute
```

### File Operation Issues

#### File Not Found

**Error:** `File not found`

**Solution:**
```php
// Check if file exists before operation
if (LaravelMulticloud::exists('path/to/file.txt')) {
    $result = LaravelMulticloud::download('path/to/file.txt');
} else {
    throw new \Exception('File does not exist');
}
```

#### Permission Denied

**Error:** `Permission denied`

**Solution:**
```php
// Check file permissions
$metadata = LaravelMulticloud::getMetadata('path/to/file.txt');
$acl = $metadata['acl'] ?? 'private';

// Upload with correct permissions
LaravelMulticloud::upload('path/to/file.txt', $content, [
    'ACL' => 'public-read' // or 'private'
]);
```

#### File Size Limit

**Error:** `File too large`

**Solution:**
```php
// Check file size before upload
$maxSize = config('multicloud.settings.upload.max_file_size', 10485760); // 10MB

if (strlen($content) > $maxSize) {
    throw new \Exception('File exceeds maximum size limit');
}

// Or increase limit in config
'upload' => [
    'max_file_size' => 52428800, // 50MB
],
```

### Provider-Specific Issues

#### AWS S3 Issues

**Error:** `Access Denied`

**Solution:**
```bash
# Check AWS credentials
aws configure list

# Verify bucket permissions
aws s3 ls s3://your-bucket-name

# Check IAM policy
aws iam get-user
```

**Error:** `Bucket does not exist`

**Solution:**
```bash
# Create bucket
aws s3 mb s3://your-bucket-name

# Or update configuration
AWS_BUCKET=existing-bucket-name
```

#### Azure Blob Issues

**Error:** `Container not found`

**Solution:**
```bash
# Create container
az storage container create --name your-container --account-name your-account

# Or update configuration
AZURE_STORAGE_CONTAINER=existing-container
```

**Error:** `Invalid connection string`

**Solution:**
```bash
# Get connection string
az storage account show-connection-string --name your-account --resource-group your-rg

# Update .env
AZURE_STORAGE_CONNECTION_STRING="DefaultEndpointsProtocol=https;AccountName=..."
```

#### GCP Issues

**Error:** `Service account not found`

**Solution:**
```bash
# Create service account
gcloud iam service-accounts create your-service-account

# Download key file
gcloud iam service-accounts keys create key.json --iam-account=your-service-account@project.iam.gserviceaccount.com

# Update configuration
GCP_KEY_FILE=path/to/key.json
```

**Error:** `Bucket not found`

**Solution:**
```bash
# Create bucket
gsutil mb gs://your-bucket-name

# Or update configuration
GCP_BUCKET=existing-bucket
```

## 🔍 Debugging

### Enable Debug Mode

```php
// In config/multicloud.php
'logging' => [
    'enabled' => true,
    'level' => 'debug',
    'channel' => 'daily',
],
```

### Check Configuration

```php
// Dump configuration
dd(config('multicloud'));

// Check specific provider
dd(config('multicloud.providers.aws'));

// Check environment variables
dd(env('AWS_ACCESS_KEY_ID'));
```

### Test Connection

```php
// Test specific provider
$result = LaravelMulticloud::driver('aws')->testConnection();
dd($result);

// Test all providers
foreach (LaravelMulticloud::getAvailableDrivers() as $provider => $name) {
    $result = LaravelMulticloud::driver($provider)->testConnection();
    echo "{$provider}: {$result['status']}\n";
}
```

### Check Logs

```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# View specific log channel
tail -f storage/logs/multicloud.log

# Check error logs
grep "ERROR" storage/logs/laravel.log
```

## 🛠️ Performance Issues

### Slow Uploads

**Issue:** File uploads are slow

**Solution:**
```php
// Use multipart upload for large files
if (strlen($content) > 10485760) { // 10MB
    $result = LaravelMulticloud::driver('aws')->upload($path, $content, [
        'multipart' => true,
        'part_size' => 5242880, // 5MB parts
    ]);
}

// Use compression
$compressed = gzcompress($content);
$result = LaravelMulticloud::upload($path, $compressed, [
    'ContentEncoding' => 'gzip',
]);
```

### Memory Issues

**Issue:** Out of memory errors

**Solution:**
```php
// Increase memory limit
ini_set('memory_limit', '512M');

// Use streaming for large files
$handle = fopen($filePath, 'r');
$result = LaravelMulticloud::upload($path, $handle);
fclose($handle);

// Process files in chunks
$chunkSize = 1024 * 1024; // 1MB
$handle = fopen($filePath, 'r');
while (!feof($handle)) {
    $chunk = fread($handle, $chunkSize);
    // Process chunk
}
fclose($handle);
```

### High Costs

**Issue:** Unexpected high costs

**Solution:**
```php
// Monitor usage regularly
$usage = LaravelMulticloud::getUsage();
if ($usage['costs']['total_cost'] > 100) {
    // Send alert
    Mail::to('admin@example.com')->send(new CostAlertMail($usage));
}

// Use cost-effective storage classes
LaravelMulticloud::driver('aws')->upload($path, $content, [
    'StorageClass' => 'STANDARD_IA', // Infrequent Access
]);

// Implement lifecycle policies
// Delete old files automatically
$oldFiles = LaravelMulticloud::list('temp/');
foreach ($oldFiles['files'] as $file) {
    if (strtotime($file['last_modified']) < strtotime('-7 days')) {
        LaravelMulticloud::delete($file['path']);
    }
}
```

## 🔒 Security Issues

### Unauthorized Access

**Issue:** Files accessible without authentication

**Solution:**
```php
// Use private ACL
LaravelMulticloud::upload($path, $content, [
    'ACL' => 'private',
]);

// Generate signed URLs for temporary access
$signedUrl = LaravelMulticloud::generateSignedUrl($path, 3600);

// Implement access control
if (!$this->hasAccess($userId, $filePath)) {
    throw new \Exception('Access denied');
}
```

### Data Leaks

**Issue:** Sensitive data exposed

**Solution:**
```php
// Encrypt sensitive files
$encrypted = encrypt($content);
LaravelMulticloud::upload($path, $encrypted, [
    'ServerSideEncryption' => 'AES256',
]);

// Use secure metadata
LaravelMulticloud::upload($path, $content, [
    'Metadata' => [
        'encrypted' => 'true',
        'user_id' => $userId,
    ],
]);
```

## 📞 Getting Help

### Before Asking for Help

1. **Check the logs** for error messages
2. **Verify configuration** is correct
3. **Test with minimal setup** to isolate the issue
4. **Search existing issues** on GitHub
5. **Check documentation** for similar problems

### Providing Information

When reporting issues, include:

1. **Laravel version**
2. **PHP version**
3. **Package version**
4. **Error messages** (full stack trace)
5. **Configuration** (without sensitive data)
6. **Steps to reproduce**
7. **Expected vs actual behavior**

### Contact Methods

- **GitHub Issues**: [Create an issue](https://github.com/subhashladumor/laravel-multicloud/issues)
- **GitHub Discussions**: [Start a discussion](https://github.com/subhashladumor/laravel-multicloud/discussions)
- **Email**: subhashladumor@gmail.com

### Useful Commands

```bash
# Check package version
composer show subhashladumor/laravel-multicloud

# Check Laravel version
php artisan --version

# Check PHP version
php --version

# Check configuration
php artisan config:show multicloud

# Test all providers
php artisan cloud:usage --all

# Check logs
tail -f storage/logs/laravel.log
```

## 📚 Additional Resources

1. **Check [Examples & Tutorials](examples.md)** for practical usage
2. **Explore [Cloud Providers](cloud-providers.md)** for provider-specific issues
3. **Read [API Reference](api-reference.md)** for method documentation
4. **Learn about [Testing](testing.md)** for testing your implementation

---

**Still having issues?** Check out [Examples & Tutorials](examples.md) for practical usage patterns!

# 📖 API Reference

Complete reference for all methods and classes in Laravel MultiCloud.

## 🏗️ Core Classes

### CloudManager

The main manager class that handles all cloud operations.

```php
use Subhashladumor\LaravelMulticloud\CloudManager;

$manager = new CloudManager($app);
```

### LaravelMulticloud Facade

Static access to cloud operations.

```php
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;
```

## 🔧 CloudManager Methods

### Driver Management

#### `driver(string $driver = null)`

Get a specific cloud provider driver.

**Parameters:**
- `$driver` (string, optional): Provider name (aws, azure, gcp, etc.)

**Returns:** `CloudProviderInterface`

**Example:**
```php
$awsDriver = LaravelMulticloud::driver('aws');
$azureDriver = LaravelMulticloud::driver('azure');
$defaultDriver = LaravelMulticloud::driver(); // Uses default provider
```

#### `getAvailableDrivers()`

Get all available cloud providers.

**Returns:** `array`

**Example:**
```php
$providers = LaravelMulticloud::getAvailableDrivers();
// Returns: ['aws' => 'Amazon Web Services', 'azure' => 'Microsoft Azure', ...]
```

### File Operations

#### `upload(string $path, mixed $file, array $options = [])`

Upload a file to cloud storage.

**Parameters:**
- `$path` (string): Remote file path
- `$file` (mixed): File content, resource, or file path
- `$options` (array, optional): Additional upload options

**Returns:** `array`

**Response Format:**
```php
[
    'status' => 'success',
    'path' => 'images/photo.jpg',
    'size' => 1024,
    'etag' => 'abc123',
    'url' => 'https://bucket.s3.amazonaws.com/images/photo.jpg',
    'provider' => 'aws',
    'timestamp' => '2024-01-01T00:00:00Z'
]
```

**Example:**
```php
// Upload string content
$result = LaravelMulticloud::upload('test/file.txt', 'Hello World!');

// Upload file resource
$file = fopen('/path/to/file.jpg', 'r');
$result = LaravelMulticloud::upload('images/photo.jpg', $file);

// Upload with options
$result = LaravelMulticloud::upload('images/photo.jpg', $content, [
    'ACL' => 'public-read',
    'CacheControl' => 'max-age=31536000',
    'ContentType' => 'image/jpeg'
]);
```

#### `download(string $path, string $localPath = null)`

Download a file from cloud storage.

**Parameters:**
- `$path` (string): Remote file path
- `$localPath` (string, optional): Local file path to save

**Returns:** `array`

**Response Format:**
```php
[
    'status' => 'success',
    'path' => 'images/photo.jpg',
    'content' => 'file content...',
    'size' => 1024,
    'provider' => 'aws',
    'timestamp' => '2024-01-01T00:00:00Z'
]
```

**Example:**
```php
// Download to memory
$result = LaravelMulticloud::download('images/photo.jpg');
$content = $result['content'];

// Download to local file
$result = LaravelMulticloud::download('images/photo.jpg', '/tmp/photo.jpg');
```

#### `delete(string $path)`

Delete a file from cloud storage.

**Parameters:**
- `$path` (string): Remote file path

**Returns:** `array`

**Response Format:**
```php
[
    'status' => 'success',
    'path' => 'images/photo.jpg',
    'provider' => 'aws',
    'timestamp' => '2024-01-01T00:00:00Z'
]
```

**Example:**
```php
$result = LaravelMulticloud::delete('images/photo.jpg');
```

#### `list(string $path = '', array $options = [])`

List files in cloud storage.

**Parameters:**
- `$path` (string, optional): Directory path
- `$options` (array, optional): List options

**Returns:** `array`

**Response Format:**
```php
[
    'status' => 'success',
    'files' => [
        [
            'name' => 'file1.txt',
            'path' => 'documents/file1.txt',
            'size' => 1024,
            'last_modified' => '2024-01-01T00:00:00Z',
            'etag' => 'abc123'
        ]
    ],
    'count' => 1,
    'provider' => 'aws',
    'timestamp' => '2024-01-01T00:00:00Z'
]
```

**Example:**
```php
// List all files
$result = LaravelMulticloud::list();

// List files in directory
$result = LaravelMulticloud::list('images/');

// List with options
$result = LaravelMulticloud::list('documents/', [
    'prefix' => '2024/',
    'max_keys' => 100
]);
```

#### `exists(string $path)`

Check if a file exists in cloud storage.

**Parameters:**
- `$path` (string): Remote file path

**Returns:** `bool`

**Example:**
```php
$exists = LaravelMulticloud::exists('images/photo.jpg');
if ($exists) {
    echo "File exists!";
}
```

#### `getMetadata(string $path)`

Get file metadata from cloud storage.

**Parameters:**
- `$path` (string): Remote file path

**Returns:** `array`

**Response Format:**
```php
[
    'status' => 'success',
    'path' => 'images/photo.jpg',
    'size' => 1024,
    'content_type' => 'image/jpeg',
    'last_modified' => '2024-01-01T00:00:00Z',
    'etag' => 'abc123',
    'provider' => 'aws',
    'timestamp' => '2024-01-01T00:00:00Z'
]
```

**Example:**
```php
$metadata = LaravelMulticloud::getMetadata('images/photo.jpg');
echo "File size: " . $metadata['size'] . " bytes";
```

#### `generateSignedUrl(string $path, int $expiration = 3600)`

Generate a signed URL for temporary access.

**Parameters:**
- `$path` (string): Remote file path
- `$expiration` (int, optional): Expiration time in seconds (default: 3600)

**Returns:** `string`

**Example:**
```php
// Generate URL valid for 1 hour
$url = LaravelMulticloud::generateSignedUrl('images/photo.jpg');

// Generate URL valid for 24 hours
$url = LaravelMulticloud::generateSignedUrl('images/photo.jpg', 86400);
```

### Provider Operations

#### `getUsage()`

Get usage statistics for the cloud provider.

**Returns:** `array`

**Response Format:**
```php
[
    'status' => 'success',
    'provider' => 'aws',
    'storage' => [
        'total_objects' => 1250,
        'total_size_bytes' => 1073741824,
        'total_size_human' => '1.00 GB'
    ],
    'requests' => [
        'get_requests' => 5000,
        'put_requests' => 2500,
        'delete_requests' => 100
    ],
    'costs' => [
        'storage_cost' => 0.023,
        'request_cost' => 0.004,
        'total_cost' => 0.027
    ],
    'timestamp' => '2024-01-01T00:00:00Z'
]
```

**Example:**
```php
$usage = LaravelMulticloud::getUsage();
echo "Total storage: " . $usage['storage']['total_size_human'];
echo "Total cost: $" . $usage['costs']['total_cost'];
```

#### `testConnection()`

Test the connection to the cloud provider.

**Returns:** `array`

**Response Format:**
```php
[
    'status' => 'success',
    'provider' => 'aws',
    'message' => 'Connection successful',
    'region' => 'us-east-1',
    'bucket' => 'my-bucket',
    'timestamp' => '2024-01-01T00:00:00Z'
]
```

**Example:**
```php
$result = LaravelMulticloud::testConnection();
if ($result['status'] === 'success') {
    echo "Connected to " . $result['provider'];
} else {
    echo "Connection failed: " . $result['message'];
}
```

## 🔌 CloudProviderInterface

All cloud providers implement this interface.

### Methods

#### `connect(array $config): bool`

Connect to the cloud provider.

**Parameters:**
- `$config` (array): Provider configuration

**Returns:** `bool`

#### `upload(string $path, mixed $file, array $options = []): array`

Upload a file.

#### `download(string $path, string $localPath = null): array`

Download a file.

#### `delete(string $path): array`

Delete a file.

#### `list(string $path = '', array $options = []): array`

List files.

#### `exists(string $path): bool`

Check if file exists.

#### `getMetadata(string $path): array`

Get file metadata.

#### `generateSignedUrl(string $path, int $expiration = 3600): string`

Generate signed URL.

#### `getUsage(): array`

Get usage statistics.

#### `getProviderName(): string`

Get provider name.

#### `getProviderVersion(): string`

Get provider version.

#### `testConnection(): array`

Test connection.

## 🎯 Provider-Specific Methods

### AWS S3

```php
$aws = LaravelMulticloud::driver('aws');

// AWS-specific options
$result = $aws->upload('file.txt', $content, [
    'ACL' => 'public-read',
    'ServerSideEncryption' => 'AES256',
    'StorageClass' => 'STANDARD_IA'
]);
```

### Microsoft Azure

```php
$azure = LaravelMulticloud::driver('azure');

// Azure-specific options
$result = $azure->upload('file.txt', $content, [
    'blob_public_access' => true,
    'cache_control' => 'max-age=31536000'
]);
```

### Google Cloud Platform

```php
$gcp = LaravelMulticloud::driver('gcp');

// GCP-specific options
$result = $gcp->upload('file.txt', $content, [
    'predefined_acl' => 'publicRead',
    'metadata' => ['custom' => 'value']
]);
```

### Cloudinary

```php
$cloudinary = LaravelMulticloud::driver('cloudinary');

// Cloudinary-specific options
$result = $cloudinary->upload('image.jpg', $content, [
    'transformation' => [
        'width' => 800,
        'height' => 600,
        'crop' => 'fill',
        'quality' => 'auto'
    ]
]);
```

## 🔄 Error Handling

### Error Response Format

```php
[
    'status' => 'error',
    'message' => 'Error description',
    'provider' => 'aws',
    'timestamp' => '2024-01-01T00:00:00Z'
]
```

### Common Error Types

1. **Connection Errors**
   - Invalid credentials
   - Network issues
   - Provider unavailable

2. **File Operation Errors**
   - File not found
   - Permission denied
   - Storage quota exceeded

3. **Configuration Errors**
   - Missing configuration
   - Invalid settings
   - Provider not enabled

### Error Handling Example

```php
try {
    $result = LaravelMulticloud::upload('file.txt', $content);
    
    if ($result['status'] === 'error') {
        throw new Exception($result['message']);
    }
    
    echo "Upload successful!";
} catch (Exception $e) {
    echo "Upload failed: " . $e->getMessage();
}
```

## 🔧 Advanced Usage

### Batch Operations

```php
// Upload multiple files
$files = ['file1.txt', 'file2.txt', 'file3.txt'];
$results = [];

foreach ($files as $file) {
    $results[] = LaravelMulticloud::upload($file, file_get_contents($file));
}
```

### Provider Switching

```php
// Switch between providers based on file type
$imageProviders = ['cloudinary', 'aws'];
$documentProviders = ['aws', 'azure', 'gcp'];

$fileExtension = pathinfo($filename, PATHINFO_EXTENSION);
$providers = in_array($fileExtension, ['jpg', 'png', 'gif']) 
    ? $imageProviders 
    : $documentProviders;

foreach ($providers as $provider) {
    try {
        $result = LaravelMulticloud::driver($provider)->upload($filename, $content);
        if ($result['status'] === 'success') {
            break; // Success, stop trying other providers
        }
    } catch (Exception $e) {
        continue; // Try next provider
    }
}
```

### Fallback Configuration

```php
// Use fallback providers
$primaryProvider = 'aws';
$fallbackProviders = ['azure', 'gcp'];

try {
    $result = LaravelMulticloud::driver($primaryProvider)->upload($path, $content);
} catch (Exception $e) {
    foreach ($fallbackProviders as $fallback) {
        try {
            $result = LaravelMulticloud::driver($fallback)->upload($path, $content);
            break;
        } catch (Exception $fallbackError) {
            continue;
        }
    }
}
```

## 📚 Next Steps

1. **Check [Examples & Tutorials](examples.md)** for practical usage patterns
2. **Explore [Cloud Providers](cloud-providers.md)** for provider-specific features
3. **Learn about [HTTP API](http-api.md)** for web integration
4. **Read [Testing](testing.md)** for testing your implementation

---

**Ready to implement?** Check out [Examples & Tutorials](examples.md) for practical usage patterns!

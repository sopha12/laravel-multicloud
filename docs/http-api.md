# 🌐 HTTP API Documentation

Complete reference for the RESTful HTTP API endpoints provided by Laravel MultiCloud.

## 📋 API Overview

The HTTP API provides RESTful endpoints for all cloud operations, allowing you to integrate cloud functionality into web applications, mobile apps, and third-party services.

### Base URL

```
https://your-domain.com/api/multicloud
```

### Authentication

The API supports various authentication methods:

- **API Key**: `X-API-Key` header
- **Bearer Token**: `Authorization: Bearer {token}` header
- **Session**: Laravel session authentication

### Response Format

All API responses follow a consistent format:

```json
{
    "status": "success|error",
    "message": "Optional message",
    "data": {},
    "timestamp": "2024-01-01T00:00:00Z"
}
```

## 📁 File Operations

### Upload File

Upload a file to cloud storage.

**Endpoint:** `POST /api/multicloud/upload`

**Parameters:**
- `file` (file, required): File to upload
- `path` (string, required): Remote file path
- `provider` (string, optional): Cloud provider (aws, azure, gcp, etc.)
- `options` (object, optional): Additional upload options

**Request Example:**
```bash
curl -X POST https://your-domain.com/api/multicloud/upload \
  -F "file=@photo.jpg" \
  -F "path=images/photo.jpg" \
  -F "provider=aws" \
  -F "options[ACL]=public-read"
```

**Response Example:**
```json
{
    "status": "success",
    "path": "images/photo.jpg",
    "size": 1024,
    "etag": "abc123",
    "url": "https://bucket.s3.amazonaws.com/images/photo.jpg",
    "provider": "aws",
    "timestamp": "2024-01-01T00:00:00Z"
}
```

### Download File

Download a file from cloud storage.

**Endpoint:** `GET /api/multicloud/download`

**Parameters:**
- `path` (string, required): Remote file path
- `provider` (string, optional): Cloud provider

**Request Example:**
```bash
curl -X GET "https://your-domain.com/api/multicloud/download?path=images/photo.jpg&provider=aws"
```

**Response:** File content with appropriate headers

### Delete File

Delete a file from cloud storage.

**Endpoint:** `DELETE /api/multicloud/delete`

**Parameters:**
- `path` (string, required): Remote file path
- `provider` (string, optional): Cloud provider

**Request Example:**
```bash
curl -X DELETE "https://your-domain.com/api/multicloud/delete?path=images/photo.jpg&provider=aws"
```

**Response Example:**
```json
{
    "status": "success",
    "path": "images/photo.jpg",
    "provider": "aws",
    "timestamp": "2024-01-01T00:00:00Z"
}
```

### List Files

List files in cloud storage.

**Endpoint:** `GET /api/multicloud/list`

**Parameters:**
- `path` (string, optional): Directory path
- `provider` (string, optional): Cloud provider
- `options` (object, optional): List options

**Request Example:**
```bash
curl -X GET "https://your-domain.com/api/multicloud/list?path=images/&provider=aws&options[max_keys]=100"
```

**Response Example:**
```json
{
    "status": "success",
    "files": [
        {
            "name": "photo1.jpg",
            "path": "images/photo1.jpg",
            "size": 1024,
            "last_modified": "2024-01-01T00:00:00Z",
            "etag": "abc123"
        }
    ],
    "count": 1,
    "provider": "aws",
    "timestamp": "2024-01-01T00:00:00Z"
}
```

### Check File Exists

Check if a file exists in cloud storage.

**Endpoint:** `GET /api/multicloud/exists`

**Parameters:**
- `path` (string, required): Remote file path
- `provider` (string, optional): Cloud provider

**Request Example:**
```bash
curl -X GET "https://your-domain.com/api/multicloud/exists?path=images/photo.jpg&provider=aws"
```

**Response Example:**
```json
{
    "status": "success",
    "exists": true,
    "path": "images/photo.jpg",
    "provider": "aws"
}
```

### Get File Metadata

Get metadata for a file in cloud storage.

**Endpoint:** `GET /api/multicloud/metadata`

**Parameters:**
- `path` (string, required): Remote file path
- `provider` (string, optional): Cloud provider

**Request Example:**
```bash
curl -X GET "https://your-domain.com/api/multicloud/metadata?path=images/photo.jpg&provider=aws"
```

**Response Example:**
```json
{
    "status": "success",
    "path": "images/photo.jpg",
    "size": 1024,
    "content_type": "image/jpeg",
    "last_modified": "2024-01-01T00:00:00Z",
    "etag": "abc123",
    "provider": "aws",
    "timestamp": "2024-01-01T00:00:00Z"
}
```

### Generate Signed URL

Generate a signed URL for temporary access to a file.

**Endpoint:** `GET /api/multicloud/signed-url`

**Parameters:**
- `path` (string, required): Remote file path
- `provider` (string, optional): Cloud provider
- `expiration` (integer, optional): Expiration time in seconds (default: 3600)

**Request Example:**
```bash
curl -X GET "https://your-domain.com/api/multicloud/signed-url?path=images/photo.jpg&provider=aws&expiration=7200"
```

**Response Example:**
```json
{
    "status": "success",
    "signed_url": "https://bucket.s3.amazonaws.com/images/photo.jpg?AWSAccessKeyId=...",
    "path": "images/photo.jpg",
    "expiration": 7200,
    "expires_at": "2024-01-01T02:00:00Z",
    "provider": "aws"
}
```

## 🔧 Provider Operations

### Get Usage Statistics

Get usage statistics for a cloud provider.

**Endpoint:** `GET /api/multicloud/usage`

**Parameters:**
- `provider` (string, optional): Cloud provider

**Request Example:**
```bash
curl -X GET "https://your-domain.com/api/multicloud/usage?provider=aws"
```

**Response Example:**
```json
{
    "status": "success",
    "provider": "aws",
    "storage": {
        "total_objects": 1250,
        "total_size_bytes": 1073741824,
        "total_size_human": "1.00 GB"
    },
    "requests": {
        "get_requests": 5000,
        "put_requests": 2500,
        "delete_requests": 100
    },
    "costs": {
        "storage_cost": 0.023,
        "request_cost": 0.004,
        "total_cost": 0.027
    },
    "timestamp": "2024-01-01T00:00:00Z"
}
```

### Test Connection

Test the connection to a cloud provider.

**Endpoint:** `GET /api/multicloud/test-connection`

**Parameters:**
- `provider` (string, optional): Cloud provider

**Request Example:**
```bash
curl -X GET "https://your-domain.com/api/multicloud/test-connection?provider=aws"
```

**Response Example:**
```json
{
    "status": "success",
    "provider": "aws",
    "message": "Connection successful",
    "region": "us-east-1",
    "bucket": "my-bucket",
    "timestamp": "2024-01-01T00:00:00Z"
}
```

### Get Available Providers

Get list of available cloud providers.

**Endpoint:** `GET /api/multicloud/providers`

**Request Example:**
```bash
curl -X GET "https://your-domain.com/api/multicloud/providers"
```

**Response Example:**
```json
{
    "status": "success",
    "providers": [
        {
            "key": "aws",
            "name": "Amazon Web Services",
            "enabled": true
        },
        {
            "key": "azure",
            "name": "Microsoft Azure",
            "enabled": true
        }
    ],
    "default": "aws",
    "count": 9
}
```

## 🔒 Authentication & Security

### API Key Authentication

```bash
curl -X GET "https://your-domain.com/api/multicloud/providers" \
  -H "X-API-Key: your-api-key"
```

### Bearer Token Authentication

```bash
curl -X GET "https://your-domain.com/api/multicloud/providers" \
  -H "Authorization: Bearer your-token"
```

### Rate Limiting

The API implements rate limiting to prevent abuse:

- **Default**: 100 requests per minute per IP
- **Authenticated**: 1000 requests per minute per user
- **Headers**: Rate limit information in response headers

```http
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1640995200
```

## 📊 Error Handling

### Error Response Format

```json
{
    "status": "error",
    "message": "Error description",
    "errors": {
        "field": ["Validation error message"]
    },
    "timestamp": "2024-01-01T00:00:00Z"
}
```

### HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created (upload successful) |
| 400 | Bad Request (validation error) |
| 401 | Unauthorized (authentication required) |
| 403 | Forbidden (insufficient permissions) |
| 404 | Not Found (file/provider not found) |
| 422 | Unprocessable Entity (validation failed) |
| 429 | Too Many Requests (rate limited) |
| 500 | Internal Server Error |

### Common Error Examples

#### Validation Error

```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "file": ["The file field is required."],
        "path": ["The path field is required."]
    },
    "timestamp": "2024-01-01T00:00:00Z"
}
```

#### Provider Error

```json
{
    "status": "error",
    "message": "Connection failed: Invalid credentials",
    "timestamp": "2024-01-01T00:00:00Z"
}
```

#### File Not Found

```json
{
    "status": "error",
    "message": "File not found",
    "timestamp": "2024-01-01T00:00:00Z"
}
```

## 🎯 Advanced Usage

### Batch Operations

Upload multiple files in a single request:

```bash
curl -X POST https://your-domain.com/api/multicloud/upload \
  -F "files[]=@file1.jpg" \
  -F "files[]=@file2.jpg" \
  -F "files[]=@file3.jpg" \
  -F "path=images/" \
  -F "provider=aws"
```

### Provider Fallback

The API supports automatic fallback to alternative providers:

```bash
curl -X POST https://your-domain.com/api/multicloud/upload \
  -F "file=@photo.jpg" \
  -F "path=images/photo.jpg" \
  -F "provider=aws" \
  -F "fallback_providers[]=azure" \
  -F "fallback_providers[]=gcp"
```

### Custom Headers

Add custom metadata to uploaded files:

```bash
curl -X POST https://your-domain.com/api/multicloud/upload \
  -F "file=@photo.jpg" \
  -F "path=images/photo.jpg" \
  -F "provider=aws" \
  -F "metadata[user_id]=123" \
  -F "metadata[category]=profile"
```

## 🔧 Integration Examples

### JavaScript/Fetch

```javascript
// Upload file
const formData = new FormData();
formData.append('file', fileInput.files[0]);
formData.append('path', 'images/photo.jpg');
formData.append('provider', 'aws');

fetch('/api/multicloud/upload', {
    method: 'POST',
    body: formData,
    headers: {
        'X-API-Key': 'your-api-key'
    }
})
.then(response => response.json())
.then(data => {
    if (data.status === 'success') {
        console.log('Upload successful:', data.url);
    } else {
        console.error('Upload failed:', data.message);
    }
});

// Get usage statistics
fetch('/api/multicloud/usage?provider=aws', {
    headers: {
        'X-API-Key': 'your-api-key'
    }
})
.then(response => response.json())
.then(data => {
    console.log('Usage:', data.storage.total_size_human);
});
```

### PHP/cURL

```php
// Upload file
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://your-domain.com/api/multicloud/upload');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'file' => new CURLFile('/path/to/file.jpg'),
    'path' => 'images/photo.jpg',
    'provider' => 'aws'
]);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-API-Key: your-api-key'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$data = json_decode($response, true);

if ($data['status'] === 'success') {
    echo "Upload successful: " . $data['url'];
} else {
    echo "Upload failed: " . $data['message'];
}

curl_close($ch);
```

### Python/Requests

```python
import requests

# Upload file
files = {'file': open('photo.jpg', 'rb')}
data = {
    'path': 'images/photo.jpg',
    'provider': 'aws'
}
headers = {'X-API-Key': 'your-api-key'}

response = requests.post(
    'https://your-domain.com/api/multicloud/upload',
    files=files,
    data=data,
    headers=headers
)

result = response.json()
if result['status'] == 'success':
    print(f"Upload successful: {result['url']}")
else:
    print(f"Upload failed: {result['message']}")

# Get usage statistics
response = requests.get(
    'https://your-domain.com/api/multicloud/usage?provider=aws',
    headers=headers
)

usage = response.json()
print(f"Storage usage: {usage['storage']['total_size_human']}")
```

## 📚 Next Steps

1. **Check [Examples & Tutorials](examples.md)** for practical usage patterns
2. **Explore [Cloud Providers](cloud-providers.md)** for provider-specific features
3. **Read [API Reference](api-reference.md)** for complete method documentation
4. **Learn about [Testing](testing.md)** for testing your implementation

---

**Ready to integrate?** Check out [Examples & Tutorials](examples.md) for practical usage patterns!

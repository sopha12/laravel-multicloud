# 🌐 Cloud Providers Guide

Detailed guide for each supported cloud provider in Laravel MultiCloud.

## 📋 Supported Providers

| Provider | Status | Special Features | Pricing Model |
|----------|--------|------------------|---------------|
| **AWS S3** | ✅ | ACL, Lifecycle, Versioning | Pay-per-use |
| **Microsoft Azure** | ✅ | Blob Storage, CDN | Pay-per-use |
| **Google Cloud Platform** | ✅ | Bucket Operations, IAM | Pay-per-use |
| **Cloudinary** | ✅ | Image/Video Transformations | Freemium |
| **Alibaba Cloud** | ✅ | OSS, CDN Integration | Pay-per-use |
| **IBM Cloud** | ✅ | COS, Object Storage | Pay-per-use |
| **DigitalOcean** | ✅ | Spaces, CDN | Fixed pricing |
| **Oracle Cloud** | ✅ | OCI Object Storage | Pay-per-use |
| **Cloudflare** | ✅ | R2, Custom Domains | Pay-per-use |

## 🏗️ AWS S3

### Overview

Amazon Simple Storage Service (S3) is the most popular cloud storage service, offering high durability, availability, and scalability.

### Features

- **ACL Management**: Fine-grained access control
- **Lifecycle Policies**: Automatic data management
- **Versioning**: File version control
- **Server-Side Encryption**: Data security
- **CloudFront Integration**: Global CDN

### Configuration

```php
// config/multicloud.php
'aws' => [
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    'bucket' => env('AWS_BUCKET'),
    'endpoint' => env('AWS_ENDPOINT'), // For S3-compatible services
    'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
    'options' => [
        'ACL' => env('AWS_ACL', 'private'),
        'CacheControl' => env('AWS_CACHE_CONTROL', 'max-age=31536000'),
        'ServerSideEncryption' => env('AWS_SERVER_SIDE_ENCRYPTION', 'AES256'),
    ],
],
```

### Environment Variables

```env
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
AWS_ACL=private
AWS_CACHE_CONTROL=max-age=31536000
```

### Usage Examples

```php
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

$aws = LaravelMulticloud::driver('aws');

// Upload with ACL
$result = $aws->upload('public/image.jpg', $content, [
    'ACL' => 'public-read',
    'CacheControl' => 'max-age=31536000'
]);

// Upload with encryption
$result = $aws->upload('secure/document.pdf', $content, [
    'ServerSideEncryption' => 'AES256',
    'ACL' => 'private'
]);

// Generate signed URL
$signedUrl = $aws->generateSignedUrl('private/file.txt', 3600);
```

### Best Practices

1. **Use appropriate ACLs** for security
2. **Enable versioning** for important data
3. **Set up lifecycle policies** for cost optimization
4. **Use CloudFront** for global distribution
5. **Enable server-side encryption** for sensitive data

## 🔵 Microsoft Azure

### Overview

Azure Blob Storage provides scalable object storage for unstructured data with enterprise-grade security and compliance features.

### Features

- **Blob Types**: Block, Page, Append blobs
- **Access Tiers**: Hot, Cool, Archive
- **CDN Integration**: Global content delivery
- **SAS Tokens**: Secure temporary access
- **Soft Delete**: Data protection

### Configuration

```php
'azure' => [
    'account_name' => env('AZURE_STORAGE_ACCOUNT_NAME'),
    'account_key' => env('AZURE_STORAGE_ACCOUNT_KEY'),
    'container' => env('AZURE_STORAGE_CONTAINER'),
    'connection_string' => env('AZURE_STORAGE_CONNECTION_STRING'),
    'sas_token' => env('AZURE_STORAGE_SAS_TOKEN'),
    'endpoint' => env('AZURE_STORAGE_ENDPOINT'),
    'options' => [
        'blob_public_access' => env('AZURE_BLOB_PUBLIC_ACCESS', false),
        'cache_control' => env('AZURE_CACHE_CONTROL', 'max-age=31536000'),
    ],
],
```

### Environment Variables

```env
AZURE_STORAGE_ACCOUNT_NAME=your-account-name
AZURE_STORAGE_ACCOUNT_KEY=your-account-key
AZURE_STORAGE_CONTAINER=your-container-name
AZURE_STORAGE_CONNECTION_STRING=DefaultEndpointsProtocol=https;AccountName=...
AZURE_BLOB_PUBLIC_ACCESS=false
```

### Usage Examples

```php
$azure = LaravelMulticloud::driver('azure');

// Upload to container
$result = $azure->upload('documents/file.pdf', $content, [
    'blob_public_access' => false,
    'cache_control' => 'max-age=31536000'
]);

// Generate SAS URL
$sasUrl = $azure->generateSignedUrl('private/file.txt', 3600);

// List blobs in container
$blobs = $azure->list('documents/');
```

### Best Practices

1. **Use appropriate access tiers** for cost optimization
2. **Enable soft delete** for data protection
3. **Use SAS tokens** for secure access
4. **Configure CDN** for better performance
5. **Set up monitoring** and alerts

## 🔴 Google Cloud Platform

### Overview

Google Cloud Storage offers high-performance object storage with global consistency and strong consistency guarantees.

### Features

- **Storage Classes**: Standard, Nearline, Coldline, Archive
- **IAM Integration**: Fine-grained permissions
- **Lifecycle Management**: Automatic data management
- **Cloud CDN**: Global content delivery
- **Dual-Region**: High availability

### Configuration

```php
'gcp' => [
    'project_id' => env('GCP_PROJECT_ID'),
    'bucket' => env('GCP_BUCKET'),
    'key_file' => env('GCP_KEY_FILE'),
    'credentials' => env('GCP_CREDENTIALS'),
    'client_email' => env('GCP_CLIENT_EMAIL'),
    'private_key' => env('GCP_PRIVATE_KEY'),
    'options' => [
        'predefined_acl' => env('GCP_PREDEFINED_ACL', 'private'),
        'cache_control' => env('GCP_CACHE_CONTROL', 'max-age=31536000'),
    ],
],
```

### Environment Variables

```env
GCP_PROJECT_ID=your-project-id
GCP_BUCKET=your-bucket-name
GCP_KEY_FILE=path/to/service-account.json
GCP_CLIENT_EMAIL=service-account@project.iam.gserviceaccount.com
GCP_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\n..."
GCP_PREDEFINED_ACL=private
```

### Usage Examples

```php
$gcp = LaravelMulticloud::driver('gcp');

// Upload with storage class
$result = $gcp->upload('backups/data.zip', $content, [
    'predefined_acl' => 'private',
    'storage_class' => 'NEARLINE'
]);

// Upload with metadata
$result = $gcp->upload('images/photo.jpg', $content, [
    'metadata' => [
        'description' => 'User profile photo',
        'uploaded_by' => 'user123'
    ]
]);

// Generate signed URL
$signedUrl = $gcp->generateSignedUrl('private/file.txt', 3600);
```

### Best Practices

1. **Choose appropriate storage classes** for cost optimization
2. **Use IAM** for fine-grained access control
3. **Enable lifecycle management** for automatic cleanup
4. **Use Cloud CDN** for global distribution
5. **Monitor usage** and costs

## 🎨 Cloudinary

### Overview

Cloudinary specializes in image and video management with powerful transformation capabilities and global CDN.

### Features

- **Image Transformations**: Resize, crop, filter, optimize
- **Video Processing**: Transcoding, thumbnails, streaming
- **AI-Powered**: Auto-tagging, moderation, optimization
- **Global CDN**: Fast delivery worldwide
- **Responsive Images**: Automatic format selection

### Configuration

```php
'cloudinary' => [
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key' => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),
    'secure' => env('CLOUDINARY_SECURE', true),
    'cdn_subdomain' => env('CLOUDINARY_CDN_SUBDOMAIN', false),
    'options' => [
        'quality' => env('CLOUDINARY_QUALITY', 'auto'),
        'format' => env('CLOUDINARY_FORMAT', 'auto'),
        'transformation' => env('CLOUDINARY_TRANSFORMATION'),
    ],
],
```

### Environment Variables

```env
CLOUDINARY_CLOUD_NAME=your-cloud-name
CLOUDINARY_API_KEY=your-api-key
CLOUDINARY_API_SECRET=your-api-secret
CLOUDINARY_SECURE=true
CLOUDINARY_QUALITY=auto
CLOUDINARY_FORMAT=auto
```

### Usage Examples

```php
$cloudinary = LaravelMulticloud::driver('cloudinary');

// Upload image with transformations
$result = $cloudinary->upload('images/photo.jpg', $content, [
    'transformation' => [
        'width' => 800,
        'height' => 600,
        'crop' => 'fill',
        'quality' => 'auto',
        'format' => 'auto'
    ]
]);

// Upload video
$result = $cloudinary->upload('videos/presentation.mp4', $content, [
    'resource_type' => 'video',
    'transformation' => [
        'width' => 1280,
        'height' => 720,
        'crop' => 'scale'
    ]
]);

// Generate responsive image URL
$responsiveUrl = $cloudinary->generateSignedUrl('images/photo.jpg', 3600);
```

### Best Practices

1. **Use auto quality and format** for optimization
2. **Implement responsive images** for better UX
3. **Use transformations** for different use cases
4. **Enable auto-tagging** for better organization
5. **Monitor bandwidth usage** for cost control

## 🏢 Alibaba Cloud

### Overview

Alibaba Cloud Object Storage Service (OSS) provides secure, reliable, and cost-effective cloud storage with global coverage.

### Features

- **Global Coverage**: Multiple regions worldwide
- **CDN Integration**: Fast content delivery
- **Security**: Encryption, access control
- **Lifecycle Management**: Automatic data management
- **Cross-Region Replication**: Data redundancy

### Configuration

```php
'alibaba' => [
    'access_key_id' => env('ALIBABA_ACCESS_KEY_ID'),
    'access_key_secret' => env('ALIBABA_ACCESS_KEY_SECRET'),
    'endpoint' => env('ALIBABA_OSS_ENDPOINT'),
    'bucket' => env('ALIBABA_OSS_BUCKET'),
    'region' => env('ALIBABA_OSS_REGION'),
    'options' => [
        'acl' => env('ALIBABA_OSS_ACL', 'private'),
        'cache_control' => env('ALIBABA_CACHE_CONTROL', 'max-age=31536000'),
    ],
],
```

### Environment Variables

```env
ALIBABA_ACCESS_KEY_ID=your-access-key-id
ALIBABA_ACCESS_KEY_SECRET=your-access-key-secret
ALIBABA_OSS_ENDPOINT=oss-cn-hangzhou.aliyuncs.com
ALIBABA_OSS_BUCKET=your-bucket-name
ALIBABA_OSS_REGION=cn-hangzhou
ALIBABA_OSS_ACL=private
```

### Usage Examples

```php
$alibaba = LaravelMulticloud::driver('alibaba');

// Upload with ACL
$result = $alibaba->upload('documents/file.pdf', $content, [
    'acl' => 'private',
    'cache_control' => 'max-age=31536000'
]);

// Upload with metadata
$result = $alibaba->upload('images/photo.jpg', $content, [
    'metadata' => [
        'content-type' => 'image/jpeg',
        'description' => 'User photo'
    ]
]);

// Generate signed URL
$signedUrl = $alibaba->generateSignedUrl('private/file.txt', 3600);
```

### Best Practices

1. **Choose appropriate regions** for latency optimization
2. **Use CDN** for better performance
3. **Enable lifecycle management** for cost control
4. **Use encryption** for sensitive data
5. **Monitor usage** and costs

## 🔵 IBM Cloud

### Overview

IBM Cloud Object Storage provides enterprise-grade object storage with high durability and global availability.

### Features

- **Enterprise Security**: Encryption, compliance
- **Global Availability**: Multiple regions
- **Flexible Pricing**: Pay-per-use model
- **API Compatibility**: S3-compatible API
- **Integration**: IBM Cloud services

### Configuration

```php
'ibm' => [
    'api_key' => env('IBM_API_KEY'),
    'service_instance_id' => env('IBM_SERVICE_INSTANCE_ID'),
    'endpoint' => env('IBM_ENDPOINT'),
    'bucket' => env('IBM_BUCKET'),
    'region' => env('IBM_REGION'),
    'options' => [
        'storage_class' => env('IBM_STORAGE_CLASS', 'standard'),
        'cache_control' => env('IBM_CACHE_CONTROL', 'max-age=31536000'),
    ],
],
```

### Environment Variables

```env
IBM_API_KEY=your-api-key
IBM_SERVICE_INSTANCE_ID=your-service-instance-id
IBM_ENDPOINT=s3.us-south.cloud-object-storage.appdomain.cloud
IBM_BUCKET=your-bucket-name
IBM_REGION=us-south
IBM_STORAGE_CLASS=standard
```

### Usage Examples

```php
$ibm = LaravelMulticloud::driver('ibm');

// Upload with storage class
$result = $ibm->upload('backups/data.zip', $content, [
    'storage_class' => 'standard',
    'cache_control' => 'max-age=31536000'
]);

// Upload with metadata
$result = $ibm->upload('documents/file.pdf', $content, [
    'metadata' => [
        'content-type' => 'application/pdf',
        'description' => 'Important document'
    ]
]);

// Generate signed URL
$signedUrl = $ibm->generateSignedUrl('private/file.txt', 3600);
```

### Best Practices

1. **Use appropriate storage classes** for cost optimization
2. **Enable encryption** for security
3. **Monitor usage** and costs
4. **Use lifecycle policies** for data management
5. **Integrate with IBM Cloud services**

## 🐳 DigitalOcean

### Overview

DigitalOcean Spaces provides simple, scalable object storage with a global CDN and S3-compatible API.

### Features

- **Simple Pricing**: Fixed monthly costs
- **Global CDN**: Fast content delivery
- **S3-Compatible**: Easy migration
- **High Availability**: 99.99% uptime
- **Developer-Friendly**: Simple API

### Configuration

```php
'digitalocean' => [
    'access_key' => env('DO_SPACES_ACCESS_KEY'),
    'secret_key' => env('DO_SPACES_SECRET_KEY'),
    'region' => env('DO_SPACES_REGION', 'nyc3'),
    'bucket' => env('DO_SPACES_BUCKET'),
    'endpoint' => env('DO_SPACES_ENDPOINT'),
    'options' => [
        'acl' => env('DO_SPACES_ACL', 'private'),
        'cache_control' => env('DO_CACHE_CONTROL', 'max-age=31536000'),
    ],
],
```

### Environment Variables

```env
DO_SPACES_ACCESS_KEY=your-access-key
DO_SPACES_SECRET_KEY=your-secret-key
DO_SPACES_REGION=nyc3
DO_SPACES_BUCKET=your-bucket-name
DO_SPACES_ENDPOINT=https://nyc3.digitaloceanspaces.com
DO_SPACES_ACL=private
```

### Usage Examples

```php
$digitalocean = LaravelMulticloud::driver('digitalocean');

// Upload with ACL
$result = $digitalocean->upload('images/photo.jpg', $content, [
    'acl' => 'public-read',
    'cache_control' => 'max-age=31536000'
]);

// Upload with metadata
$result = $digitalocean->upload('documents/file.pdf', $content, [
    'metadata' => [
        'content-type' => 'application/pdf',
        'description' => 'Document file'
    ]
]);

// Generate signed URL
$signedUrl = $digitalocean->generateSignedUrl('private/file.txt', 3600);
```

### Best Practices

1. **Use CDN** for better performance
2. **Choose appropriate regions** for latency
3. **Monitor usage** for cost control
4. **Use lifecycle policies** for data management
5. **Enable versioning** for important data

## 🏛️ Oracle Cloud

### Overview

Oracle Cloud Infrastructure Object Storage provides high-performance object storage with enterprise-grade security and compliance.

### Features

- **Enterprise Security**: Encryption, compliance
- **High Performance**: Low latency, high throughput
- **Global Availability**: Multiple regions
- **Cost Optimization**: Flexible pricing
- **Integration**: Oracle Cloud services

### Configuration

```php
'oracle' => [
    'user_ocid' => env('ORACLE_USER_OCID'),
    'tenancy_ocid' => env('ORACLE_TENANCY_OCID'),
    'fingerprint' => env('ORACLE_FINGERPRINT'),
    'private_key' => env('ORACLE_PRIVATE_KEY'),
    'region' => env('ORACLE_REGION', 'us-ashburn-1'),
    'bucket' => env('ORACLE_BUCKET'),
    'namespace' => env('ORACLE_NAMESPACE'),
    'options' => [
        'storage_tier' => env('ORACLE_STORAGE_TIER', 'standard'),
        'cache_control' => env('ORACLE_CACHE_CONTROL', 'max-age=31536000'),
    ],
],
```

### Environment Variables

```env
ORACLE_USER_OCID=ocid1.user.oc1..your-user-ocid
ORACLE_TENANCY_OCID=ocid1.tenancy.oc1..your-tenancy-ocid
ORACLE_FINGERPRINT=your-fingerprint
ORACLE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\n..."
ORACLE_REGION=us-ashburn-1
ORACLE_BUCKET=your-bucket-name
ORACLE_NAMESPACE=your-namespace
```

### Usage Examples

```php
$oracle = LaravelMulticloud::driver('oracle');

// Upload with storage tier
$result = $oracle->upload('backups/data.zip', $content, [
    'storage_tier' => 'standard',
    'cache_control' => 'max-age=31536000'
]);

// Upload with metadata
$result = $oracle->upload('documents/file.pdf', $content, [
    'metadata' => [
        'content-type' => 'application/pdf',
        'description' => 'Important document'
    ]
]);

// Generate signed URL
$signedUrl = $oracle->generateSignedUrl('private/file.txt', 3600);
```

### Best Practices

1. **Use appropriate storage tiers** for cost optimization
2. **Enable encryption** for security
3. **Monitor usage** and costs
4. **Use lifecycle policies** for data management
5. **Integrate with Oracle Cloud services**

## ☁️ Cloudflare

### Overview

Cloudflare R2 provides object storage with zero egress fees and global performance through Cloudflare's network.

### Features

- **Zero Egress Fees**: No charges for data transfer
- **Global Network**: Fast delivery worldwide
- **S3-Compatible**: Easy migration
- **Custom Domains**: Branded URLs
- **Developer-Friendly**: Simple API

### Configuration

```php
'cloudflare' => [
    'account_id' => env('CLOUDFLARE_ACCOUNT_ID'),
    'access_key_id' => env('CLOUDFLARE_ACCESS_KEY_ID'),
    'secret_access_key' => env('CLOUDFLARE_SECRET_ACCESS_KEY'),
    'bucket' => env('CLOUDFLARE_BUCKET'),
    'custom_domain' => env('CLOUDFLARE_CUSTOM_DOMAIN'),
    'options' => [
        'cache_control' => env('CLOUDFLARE_CACHE_CONTROL', 'max-age=31536000'),
        'public_access' => env('CLOUDFLARE_PUBLIC_ACCESS', false),
    ],
],
```

### Environment Variables

```env
CLOUDFLARE_ACCOUNT_ID=your-account-id
CLOUDFLARE_ACCESS_KEY_ID=your-access-key-id
CLOUDFLARE_SECRET_ACCESS_KEY=your-secret-access-key
CLOUDFLARE_BUCKET=your-bucket-name
CLOUDFLARE_CUSTOM_DOMAIN=your-custom-domain.com
CLOUDFLARE_PUBLIC_ACCESS=false
```

### Usage Examples

```php
$cloudflare = LaravelMulticloud::driver('cloudflare');

// Upload with custom domain
$result = $cloudflare->upload('images/photo.jpg', $content, [
    'public_access' => true,
    'cache_control' => 'max-age=31536000'
]);

// Upload with metadata
$result = $cloudflare->upload('documents/file.pdf', $content, [
    'metadata' => [
        'content-type' => 'application/pdf',
        'description' => 'Document file'
    ]
]);

// Generate signed URL
$signedUrl = $cloudflare->generateSignedUrl('private/file.txt', 3600);
```

### Best Practices

1. **Use custom domains** for branding
2. **Enable public access** for public content
3. **Monitor usage** for cost control
4. **Use lifecycle policies** for data management
5. **Leverage global network** for performance

## 🔄 Provider Comparison

| Feature | AWS | Azure | GCP | Cloudinary | Alibaba | IBM | DO | Oracle | Cloudflare |
|---------|-----|-------|-----|------------|---------|-----|----|---------|-----------| 
| **Pricing** | Pay-per-use | Pay-per-use | Pay-per-use | Freemium | Pay-per-use | Pay-per-use | Fixed | Pay-per-use | Pay-per-use |
| **CDN** | CloudFront | Azure CDN | Cloud CDN | Built-in | Alibaba CDN | IBM CDN | Built-in | Oracle CDN | Built-in |
| **Encryption** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Lifecycle** | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Versioning** | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ | ✅ | ✅ | ❌ |
| **S3 Compatible** | ✅ | ❌ | ❌ | ❌ | ✅ | ✅ | ✅ | ❌ | ✅ |
| **Global Regions** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |

## 🎯 Choosing the Right Provider

### For Images/Videos
- **Cloudinary**: Best for transformations and optimization
- **AWS S3 + CloudFront**: Best for scale and performance
- **Cloudflare R2**: Best for cost (zero egress fees)

### For Documents
- **AWS S3**: Best for enterprise features
- **Azure Blob**: Best for Microsoft ecosystem
- **GCP**: Best for Google ecosystem

### For Cost Optimization
- **Cloudflare R2**: Zero egress fees
- **DigitalOcean Spaces**: Fixed pricing
- **Alibaba Cloud**: Competitive pricing

### For Enterprise
- **AWS S3**: Most mature and feature-rich
- **Azure Blob**: Best for Microsoft compliance
- **Oracle Cloud**: Best for Oracle ecosystem

## 📚 Next Steps

1. **Read [Examples & Tutorials](examples.md)** for practical usage
2. **Check [API Reference](api-reference.md)** for method documentation
3. **Explore [HTTP API](http-api.md)** for web integration
4. **Learn about [Testing](testing.md)** for testing your implementation

---

**Ready to choose a provider?** Check out [Examples & Tutorials](examples.md) for practical usage patterns!

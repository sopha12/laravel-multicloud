# 🎯 Examples & Tutorials

Practical examples and tutorials for using Laravel MultiCloud in real-world scenarios.

## 🚀 Quick Start Examples

### Basic File Upload

```php
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

// Upload a simple text file
$result = LaravelMulticloud::upload('documents/hello.txt', 'Hello World!');

if ($result['status'] === 'success') {
    echo "File uploaded successfully!";
    echo "URL: " . $result['url'];
} else {
    echo "Upload failed: " . $result['message'];
}
```

### Upload with Specific Provider

```php
// Upload to AWS S3
$result = LaravelMulticloud::driver('aws')->upload('images/photo.jpg', $imageContent);

// Upload to Azure Blob Storage
$result = LaravelMulticloud::driver('azure')->upload('documents/file.pdf', $pdfContent);

// Upload to Google Cloud Storage
$result = LaravelMulticloud::driver('gcp')->upload('backups/data.zip', $backupContent);
```

### Download and Save Locally

```php
// Download to memory
$result = LaravelMulticloud::download('images/photo.jpg');
$imageContent = $result['content'];

// Download to local file
$result = LaravelMulticloud::download('documents/file.pdf', '/tmp/file.pdf');
```

## 📁 File Management Examples

### Upload Multiple Files

```php
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

class FileUploadController extends Controller
{
    public function uploadMultiple(Request $request)
    {
        $files = $request->file('files');
        $results = [];
        
        foreach ($files as $file) {
            $path = 'uploads/' . time() . '_' . $file->getClientOriginalName();
            $result = LaravelMulticloud::upload($path, $file->getContent(), [
                'ContentType' => $file->getMimeType(),
                'ACL' => 'private'
            ]);
            
            $results[] = [
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'status' => $result['status'],
                'url' => $result['url'] ?? null
            ];
        }
        
        return response()->json(['uploads' => $results]);
    }
}
```

### File Organization by Type

```php
class FileManager
{
    public function uploadByType($file, $content)
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return $this->uploadImage($file, $content);
                
            case 'pdf':
            case 'doc':
            case 'docx':
                return $this->uploadDocument($file, $content);
                
            case 'mp4':
            case 'avi':
            case 'mov':
                return $this->uploadVideo($file, $content);
                
            default:
                return $this->uploadGeneric($file, $content);
        }
    }
    
    private function uploadImage($file, $content)
    {
        // Use Cloudinary for images
        return LaravelMulticloud::driver('cloudinary')->upload($file, $content, [
            'transformation' => [
                'width' => 800,
                'height' => 600,
                'crop' => 'fill',
                'quality' => 'auto'
            ]
        ]);
    }
    
    private function uploadDocument($file, $content)
    {
        // Use AWS for documents
        return LaravelMulticloud::driver('aws')->upload($file, $content, [
            'ACL' => 'private',
            'ContentType' => 'application/pdf'
        ]);
    }
    
    private function uploadVideo($file, $content)
    {
        // Use Cloudinary for videos
        return LaravelMulticloud::driver('cloudinary')->upload($file, $content, [
            'resource_type' => 'video',
            'transformation' => [
                'width' => 1280,
                'height' => 720,
                'crop' => 'scale'
            ]
        ]);
    }
    
    private function uploadGeneric($file, $content)
    {
        // Use default provider for other files
        return LaravelMulticloud::upload($file, $content);
    }
}
```

### File Cleanup and Management

```php
class FileCleanupService
{
    public function cleanupOldFiles($days = 30)
    {
        $providers = ['aws', 'azure', 'gcp'];
        $cutoffDate = now()->subDays($days);
        
        foreach ($providers as $provider) {
            $files = LaravelMulticloud::driver($provider)->list('temp/');
            
            foreach ($files['files'] as $file) {
                $lastModified = \Carbon\Carbon::parse($file['last_modified']);
                
                if ($lastModified->lt($cutoffDate)) {
                    LaravelMulticloud::driver($provider)->delete($file['path']);
                    echo "Deleted old file: " . $file['path'] . "\n";
                }
            }
        }
    }
    
    public function archiveFiles($sourceProvider, $archiveProvider, $path)
    {
        $files = LaravelMulticloud::driver($sourceProvider)->list($path);
        
        foreach ($files['files'] as $file) {
            // Download from source
            $content = LaravelMulticloud::driver($sourceProvider)->download($file['path']);
            
            // Upload to archive
            LaravelMulticloud::driver($archiveProvider)->upload(
                'archive/' . $file['path'],
                $content['content']
            );
            
            // Delete from source
            LaravelMulticloud::driver($sourceProvider)->delete($file['path']);
            
            echo "Archived: " . $file['path'] . "\n";
        }
    }
}
```

## 🖼️ Image Processing Examples

### Image Upload with Cloudinary

```php
class ImageUploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240', // 10MB max
            'transformations' => 'sometimes|array'
        ]);
        
        $file = $request->file('image');
        $transformations = $request->input('transformations', []);
        
        // Default transformations
        $defaultTransformations = [
            'width' => 800,
            'height' => 600,
            'crop' => 'fill',
            'quality' => 'auto',
            'format' => 'auto'
        ];
        
        $finalTransformations = array_merge($defaultTransformations, $transformations);
        
        $result = LaravelMulticloud::driver('cloudinary')->upload(
            'images/' . time() . '_' . $file->getClientOriginalName(),
            $file->getContent(),
            ['transformation' => $finalTransformations]
        );
        
        return response()->json([
            'status' => 'success',
            'url' => $result['url'],
            'transformations' => $finalTransformations
        ]);
    }
    
    public function generateThumbnails($imagePath)
    {
        $sizes = [
            'small' => ['width' => 150, 'height' => 150],
            'medium' => ['width' => 400, 'height' => 300],
            'large' => ['width' => 800, 'height' => 600]
        ];
        
        $thumbnails = [];
        
        foreach ($sizes as $size => $dimensions) {
            $url = LaravelMulticloud::driver('cloudinary')->generateSignedUrl($imagePath, 3600);
            $thumbnails[$size] = $url . '&w_' . $dimensions['width'] . ',h_' . $dimensions['height'] . ',c_fill';
        }
        
        return $thumbnails;
    }
}
```

### Responsive Images

```php
class ResponsiveImageService
{
    public function generateResponsiveUrls($imagePath)
    {
        $breakpoints = [
            'mobile' => 480,
            'tablet' => 768,
            'desktop' => 1200,
            'large' => 1920
        ];
        
        $responsiveUrls = [];
        
        foreach ($breakpoints as $device => $width) {
            $url = LaravelMulticloud::driver('cloudinary')->generateSignedUrl($imagePath, 3600);
            $responsiveUrls[$device] = $url . '&w_' . $width . ',q_auto,f_auto';
        }
        
        return $responsiveUrls;
    }
    
    public function generateSrcSet($imagePath)
    {
        $sizes = [320, 640, 960, 1280, 1920];
        $srcSet = [];
        
        foreach ($sizes as $size) {
            $url = LaravelMulticloud::driver('cloudinary')->generateSignedUrl($imagePath, 3600);
            $srcSet[] = $url . '&w_' . $size . ',q_auto,f_auto ' . $size . 'w';
        }
        
        return implode(', ', $srcSet);
    }
}
```

## 🔒 Security Examples

### Secure File Upload

```php
class SecureFileUpload
{
    public function uploadSecureFile($file, $content, $userId)
    {
        // Generate secure path
        $securePath = 'secure/' . $userId . '/' . uniqid() . '_' . $file;
        
        // Upload with encryption
        $result = LaravelMulticloud::driver('aws')->upload($securePath, $content, [
            'ServerSideEncryption' => 'AES256',
            'ACL' => 'private',
            'Metadata' => [
                'user_id' => $userId,
                'uploaded_at' => now()->toISOString()
            ]
        ]);
        
        return $result;
    }
    
    public function generateSecureUrl($filePath, $userId, $expiration = 3600)
    {
        // Verify user has access to file
        $metadata = LaravelMulticloud::driver('aws')->getMetadata($filePath);
        
        if ($metadata['metadata']['user_id'] !== $userId) {
            throw new \Exception('Access denied');
        }
        
        // Generate signed URL
        return LaravelMulticloud::driver('aws')->generateSignedUrl($filePath, $expiration);
    }
}
```

### Access Control

```php
class FileAccessControl
{
    public function checkAccess($filePath, $userId, $permission = 'read')
    {
        $metadata = LaravelMulticloud::driver('aws')->getMetadata($filePath);
        $fileOwner = $metadata['metadata']['owner_id'] ?? null;
        
        if ($fileOwner === $userId) {
            return true; // Owner has full access
        }
        
        // Check shared permissions
        $sharedWith = json_decode($metadata['metadata']['shared_with'] ?? '[]', true);
        
        foreach ($sharedWith as $share) {
            if ($share['user_id'] === $userId && in_array($permission, $share['permissions'])) {
                return true;
            }
        }
        
        return false;
    }
    
    public function shareFile($filePath, $userId, $permissions = ['read'])
    {
        $metadata = LaravelMulticloud::driver('aws')->getMetadata($filePath);
        $sharedWith = json_decode($metadata['metadata']['shared_with'] ?? '[]', true);
        
        $sharedWith[] = [
            'user_id' => $userId,
            'permissions' => $permissions,
            'shared_at' => now()->toISOString()
        ];
        
        // Update metadata
        LaravelMulticloud::driver('aws')->upload($filePath, $metadata['content'], [
            'Metadata' => [
                'shared_with' => json_encode($sharedWith)
            ]
        ]);
    }
}
```

## 📊 Monitoring and Analytics

### Usage Tracking

```php
class UsageTracker
{
    public function trackUpload($provider, $fileSize, $fileType)
    {
        $usage = LaravelMulticloud::driver($provider)->getUsage();
        
        // Log usage
        \Log::info('File upload tracked', [
            'provider' => $provider,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'total_usage' => $usage['storage']['total_size_human'],
            'cost' => $usage['costs']['total_cost']
        ]);
        
        // Update database
        \DB::table('usage_logs')->insert([
            'provider' => $provider,
            'file_size' => $fileSize,
            'file_type' => $fileType,
            'uploaded_at' => now()
        ]);
    }
    
    public function getUsageReport($provider, $days = 30)
    {
        $usage = LaravelMulticloud::driver($provider)->getUsage();
        
        $dbUsage = \DB::table('usage_logs')
            ->where('provider', $provider)
            ->where('uploaded_at', '>=', now()->subDays($days))
            ->selectRaw('
                file_type,
                COUNT(*) as file_count,
                SUM(file_size) as total_size,
                AVG(file_size) as avg_size
            ')
            ->groupBy('file_type')
            ->get();
        
        return [
            'provider' => $provider,
            'period' => $days . ' days',
            'storage' => $usage['storage'],
            'requests' => $usage['requests'],
            'costs' => $usage['costs'],
            'file_types' => $dbUsage
        ];
    }
}
```

### Cost Monitoring

```php
class CostMonitor
{
    public function checkCostThresholds()
    {
        $providers = ['aws', 'azure', 'gcp'];
        $thresholds = config('multicloud.cost_thresholds', []);
        
        foreach ($providers as $provider) {
            $usage = LaravelMulticloud::driver($provider)->getUsage();
            $currentCost = $usage['costs']['total_cost'];
            
            if (isset($thresholds[$provider]) && $currentCost > $thresholds[$provider]) {
                $this->sendCostAlert($provider, $currentCost, $thresholds[$provider]);
            }
        }
    }
    
    private function sendCostAlert($provider, $currentCost, $threshold)
    {
        \Mail::to(config('mail.admin_email'))->send(new CostAlertMail(
            $provider,
            $currentCost,
            $threshold
        ));
    }
    
    public function getCostProjection($provider, $days = 30)
    {
        $usage = LaravelMulticloud::driver($provider)->getUsage();
        $dailyCost = $usage['costs']['total_cost'] / $days;
        
        return [
            'provider' => $provider,
            'current_daily_cost' => $dailyCost,
            'projected_monthly_cost' => $dailyCost * 30,
            'projected_yearly_cost' => $dailyCost * 365
        ];
    }
}
```

## 🔄 Backup and Sync Examples

### Automated Backup

```php
class BackupService
{
    public function createBackup($sourceProvider, $backupProvider, $paths = [])
    {
        $backupId = 'backup_' . now()->format('Y-m-d_H-i-s');
        $results = [];
        
        foreach ($paths as $path) {
            $files = LaravelMulticloud::driver($sourceProvider)->list($path);
            
            foreach ($files['files'] as $file) {
                // Download from source
                $content = LaravelMulticloud::driver($sourceProvider)->download($file['path']);
                
                // Upload to backup
                $backupPath = $backupId . '/' . $file['path'];
                $result = LaravelMulticloud::driver($backupProvider)->upload(
                    $backupPath,
                    $content['content']
                );
                
                $results[] = [
                    'original_path' => $file['path'],
                    'backup_path' => $backupPath,
                    'status' => $result['status']
                ];
            }
        }
        
        return $results;
    }
    
    public function restoreBackup($backupProvider, $sourceProvider, $backupId)
    {
        $files = LaravelMulticloud::driver($backupProvider)->list($backupId . '/');
        $results = [];
        
        foreach ($files['files'] as $file) {
            // Download from backup
            $content = LaravelMulticloud::driver($backupProvider)->download($file['path']);
            
            // Upload to source
            $originalPath = str_replace($backupId . '/', '', $file['path']);
            $result = LaravelMulticloud::driver($sourceProvider)->upload(
                $originalPath,
                $content['content']
            );
            
            $results[] = [
                'backup_path' => $file['path'],
                'restored_path' => $originalPath,
                'status' => $result['status']
            ];
        }
        
        return $results;
    }
}
```

### Cross-Provider Sync

```php
class SyncService
{
    public function syncProviders($sourceProvider, $targetProviders, $path)
    {
        $files = LaravelMulticloud::driver($sourceProvider)->list($path);
        $results = [];
        
        foreach ($files['files'] as $file) {
            // Download from source
            $content = LaravelMulticloud::driver($sourceProvider)->download($file['path']);
            
            // Upload to all target providers
            foreach ($targetProviders as $targetProvider) {
                $result = LaravelMulticloud::driver($targetProvider)->upload(
                    $file['path'],
                    $content['content']
                );
                
                $results[] = [
                    'file' => $file['path'],
                    'target' => $targetProvider,
                    'status' => $result['status']
                ];
            }
        }
        
        return $results;
    }
    
    public function syncWithFallback($primaryProvider, $fallbackProviders, $path)
    {
        $files = LaravelMulticloud::driver($primaryProvider)->list($path);
        $results = [];
        
        foreach ($files['files'] as $file) {
            $synced = false;
            
            // Try primary provider first
            try {
                $content = LaravelMulticloud::driver($primaryProvider)->download($file['path']);
                $synced = true;
            } catch (\Exception $e) {
                // Try fallback providers
                foreach ($fallbackProviders as $fallbackProvider) {
                    try {
                        $content = LaravelMulticloud::driver($fallbackProvider)->download($file['path']);
                        $synced = true;
                        break;
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
            
            $results[] = [
                'file' => $file['path'],
                'synced' => $synced,
                'source' => $synced ? 'available' : 'unavailable'
            ];
        }
        
        return $results;
    }
}
```

## 🎯 Real-World Use Cases

### E-commerce Product Images

```php
class ProductImageService
{
    public function uploadProductImages($productId, $images)
    {
        $results = [];
        
        foreach ($images as $index => $image) {
            $path = "products/{$productId}/image_{$index}.jpg";
            
            $result = LaravelMulticloud::driver('cloudinary')->upload($path, $image, [
                'transformation' => [
                    'width' => 800,
                    'height' => 800,
                    'crop' => 'fill',
                    'quality' => 'auto',
                    'format' => 'auto'
                ]
            ]);
            
            $results[] = [
                'index' => $index,
                'url' => $result['url'],
                'thumbnail' => $this->generateThumbnail($result['url'])
            ];
        }
        
        return $results;
    }
    
    private function generateThumbnail($imageUrl)
    {
        return $imageUrl . '&w_150,h_150,c_fill';
    }
}
```

### Document Management System

```php
class DocumentManager
{
    public function uploadDocument($userId, $document, $metadata = [])
    {
        $path = "documents/{$userId}/" . time() . "_" . $document->getClientOriginalName();
        
        $result = LaravelMulticloud::driver('aws')->upload($path, $document->getContent(), [
            'ACL' => 'private',
            'ContentType' => $document->getMimeType(),
            'Metadata' => array_merge($metadata, [
                'user_id' => $userId,
                'original_name' => $document->getClientOriginalName(),
                'uploaded_at' => now()->toISOString()
            ])
        ]);
        
        return $result;
    }
    
    public function generateDocumentUrl($documentId, $userId)
    {
        $metadata = LaravelMulticloud::driver('aws')->getMetadata("documents/{$userId}/{$documentId}");
        
        if ($metadata['metadata']['user_id'] !== $userId) {
            throw new \Exception('Access denied');
        }
        
        return LaravelMulticloud::driver('aws')->generateSignedUrl(
            "documents/{$userId}/{$documentId}",
            3600
        );
    }
}
```

## 📚 Next Steps

1. **Check [API Reference](api-reference.md)** for complete method documentation
2. **Explore [Cloud Providers](cloud-providers.md)** for provider-specific features
3. **Learn about [HTTP API](http-api.md)** for web integration
4. **Read [Testing](testing.md)** for testing your implementation

---

**Ready to implement?** Check out the [API Reference](api-reference.md) for complete method documentation!

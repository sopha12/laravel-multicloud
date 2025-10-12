# 🧪 Testing Guide

Comprehensive guide for testing Laravel MultiCloud in your applications.

## 📋 Testing Overview

Laravel MultiCloud includes a complete test suite and provides tools for testing your cloud integrations.

### Test Structure

```
tests/
├── CloudTest.php              # Main test suite
├── Unit/                      # Unit tests
│   ├── CloudManagerTest.php
│   ├── ProviderTest.php
│   └── FacadeTest.php
├── Integration/               # Integration tests
│   ├── ApiTest.php
│   ├── CommandTest.php
│   └── ControllerTest.php
└── Feature/                  # Feature tests
    ├── UploadTest.php
    ├── DownloadTest.php
    └── UsageTest.php
```

## 🚀 Running Tests

### Basic Test Commands

```bash
# Run all tests
composer test

# Run with coverage
composer test-coverage

# Run specific test file
phpunit tests/CloudTest.php

# Run specific test method
phpunit --filter testUpload tests/CloudTest.php

# Run with verbose output
phpunit --verbose tests/CloudTest.php
```

### Test Configuration

Create `phpunit.xml` in your project root:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Integration">
            <directory>tests/Integration</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <coverage>
        <include>
            <directory suffix=".php">src</directory>
        </include>
    </coverage>
</phpunit>
```

## 🔧 Unit Testing

### Testing CloudManager

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use Subhashladumor\LaravelMulticloud\CloudManager;
use Subhashladumor\LaravelMulticloud\Providers\AwsProvider;

class CloudManagerTest extends TestCase
{
    public function testDefaultDriverResolution()
    {
        $manager = new CloudManager($this->app);
        $driver = $manager->driver();
        
        $this->assertInstanceOf(AwsProvider::class, $driver);
    }
    
    public function testSpecificDriverResolution()
    {
        $manager = new CloudManager($this->app);
        
        $awsDriver = $manager->driver('aws');
        $this->assertInstanceOf(AwsProvider::class, $awsDriver);
        
        $azureDriver = $manager->driver('azure');
        $this->assertInstanceOf(AzureProvider::class, $azureDriver);
    }
    
    public function testConvenienceMethods()
    {
        $manager = new CloudManager($this->app);
        
        // Test upload
        $result = $manager->upload('test/file.txt', 'Hello World!');
        $this->assertEquals('success', $result['status']);
        
        // Test download
        $result = $manager->download('test/file.txt');
        $this->assertEquals('success', $result['status']);
        
        // Test delete
        $result = $manager->delete('test/file.txt');
        $this->assertEquals('success', $result['status']);
    }
}
```

### Testing Providers

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use Subhashladumor\LaravelMulticloud\Providers\AwsProvider;

class AwsProviderTest extends TestCase
{
    private AwsProvider $provider;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->provider = new AwsProvider();
        $this->provider->connect([
            'key' => 'test-key',
            'secret' => 'test-secret',
            'region' => 'us-east-1',
            'bucket' => 'test-bucket'
        ]);
    }
    
    public function testConnection()
    {
        $connected = $this->provider->connect([
            'key' => 'test-key',
            'secret' => 'test-secret',
            'region' => 'us-east-1',
            'bucket' => 'test-bucket'
        ]);
        
        $this->assertTrue($connected);
    }
    
    public function testUpload()
    {
        $result = $this->provider->upload('test/file.txt', 'Hello World!');
        
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('aws', $result['provider']);
        $this->assertArrayHasKey('url', $result);
    }
    
    public function testDownload()
    {
        $result = $this->provider->download('test/file.txt');
        
        $this->assertEquals('success', $result['status']);
        $this->assertStringContainsString('test/file.txt', $result['content']);
    }
    
    public function testDelete()
    {
        $result = $this->provider->delete('test/file.txt');
        
        $this->assertEquals('success', $result['status']);
    }
    
    public function testList()
    {
        $result = $this->provider->list();
        
        $this->assertEquals('success', $result['status']);
        $this->assertIsArray($result['files']);
    }
    
    public function testExists()
    {
        $exists = $this->provider->exists('test/file.txt');
        
        $this->assertIsBool($exists);
    }
    
    public function testGetMetadata()
    {
        $metadata = $this->provider->getMetadata('test/file.txt');
        
        $this->assertEquals('success', $metadata['status']);
        $this->assertArrayHasKey('size', $metadata);
    }
    
    public function testGenerateSignedUrl()
    {
        $url = $this->provider->generateSignedUrl('test/file.txt', 3600);
        
        $this->assertIsString($url);
        $this->assertStringContainsString('test/file.txt', $url);
    }
    
    public function testGetUsage()
    {
        $usage = $this->provider->getUsage();
        
        $this->assertEquals('success', $usage['status']);
        $this->assertArrayHasKey('storage', $usage);
        $this->assertArrayHasKey('requests', $usage);
        $this->assertArrayHasKey('costs', $usage);
    }
    
    public function testTestConnection()
    {
        $result = $this->provider->testConnection();
        
        $this->assertEquals('success', $result['status']);
        $this->assertEquals('aws', $result['provider']);
    }
    
    public function testGetProviderName()
    {
        $name = $this->provider->getProviderName();
        
        $this->assertEquals('Amazon Web Services (AWS)', $name);
    }
    
    public function testGetProviderVersion()
    {
        $version = $this->provider->getProviderVersion();
        
        $this->assertEquals('1.0.0', $version);
    }
}
```

### Testing Facade

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

class FacadeTest extends TestCase
{
    public function testFacadeAccess()
    {
        $driver = LaravelMulticloud::driver();
        $this->assertInstanceOf(AwsProvider::class, $driver);
    }
    
    public function testFacadeMethods()
    {
        // Test upload
        $result = LaravelMulticloud::upload('test/file.txt', 'Hello World!');
        $this->assertEquals('success', $result['status']);
        
        // Test download
        $result = LaravelMulticloud::download('test/file.txt');
        $this->assertEquals('success', $result['status']);
        
        // Test delete
        $result = LaravelMulticloud::delete('test/file.txt');
        $this->assertEquals('success', $result['status']);
    }
}
```

## 🔗 Integration Testing

### Testing API Endpoints

```php
<?php

namespace Tests\Integration;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ApiTest extends TestCase
{
    public function testUploadEndpoint()
    {
        $file = UploadedFile::fake()->image('photo.jpg');
        
        $response = $this->postJson('/api/multicloud/upload', [
            'file' => $file,
            'path' => 'images/photo.jpg',
            'provider' => 'aws'
        ]);
        
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success'
                 ])
                 ->assertJsonStructure([
                     'status',
                     'path',
                     'size',
                     'url',
                     'provider'
                 ]);
    }
    
    public function testDownloadEndpoint()
    {
        $response = $this->getJson('/api/multicloud/download?path=images/photo.jpg&provider=aws');
        
        $response->assertStatus(200);
    }
    
    public function testDeleteEndpoint()
    {
        $response = $this->deleteJson('/api/multicloud/delete?path=images/photo.jpg&provider=aws');
        
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success'
                 ]);
    }
    
    public function testListEndpoint()
    {
        $response = $this->getJson('/api/multicloud/list?provider=aws');
        
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success'
                 ])
                 ->assertJsonStructure([
                     'status',
                     'files',
                     'count',
                     'provider'
                 ]);
    }
    
    public function testExistsEndpoint()
    {
        $response = $this->getJson('/api/multicloud/exists?path=images/photo.jpg&provider=aws');
        
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success'
                 ])
                 ->assertJsonStructure([
                     'status',
                     'exists',
                     'path',
                     'provider'
                 ]);
    }
    
    public function testMetadataEndpoint()
    {
        $response = $this->getJson('/api/multicloud/metadata?path=images/photo.jpg&provider=aws');
        
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success'
                 ])
                 ->assertJsonStructure([
                     'status',
                     'path',
                     'size',
                     'content_type',
                     'provider'
                 ]);
    }
    
    public function testSignedUrlEndpoint()
    {
        $response = $this->getJson('/api/multicloud/signed-url?path=images/photo.jpg&provider=aws&expiration=3600');
        
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success'
                 ])
                 ->assertJsonStructure([
                     'status',
                     'signed_url',
                     'path',
                     'expiration',
                     'expires_at',
                     'provider'
                 ]);
    }
    
    public function testUsageEndpoint()
    {
        $response = $this->getJson('/api/multicloud/usage?provider=aws');
        
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success'
                 ])
                 ->assertJsonStructure([
                     'status',
                     'provider',
                     'storage',
                     'requests',
                     'costs'
                 ]);
    }
    
    public function testTestConnectionEndpoint()
    {
        $response = $this->getJson('/api/multicloud/test-connection?provider=aws');
        
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success'
                 ])
                 ->assertJsonStructure([
                     'status',
                     'provider',
                     'message'
                 ]);
    }
    
    public function testProvidersEndpoint()
    {
        $response = $this->getJson('/api/multicloud/providers');
        
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success'
                 ])
                 ->assertJsonStructure([
                     'status',
                     'providers',
                     'default',
                     'count'
                 ]);
    }
}
```

### Testing Artisan Commands

```php
<?php

namespace Tests\Integration;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class CommandTest extends TestCase
{
    public function testCloudDeployCommand()
    {
        $this->artisan('cloud:deploy', [
            '--provider' => 'aws',
            '--dry-run' => true
        ])
        ->expectsOutput('🚀 Starting deployment to aws...')
        ->expectsOutput('✅ Connection successful!')
        ->expectsOutput('🔍 DRY RUN MODE - No actual deployment will occur')
        ->assertExitCode(0);
    }
    
    public function testCloudUsageCommand()
    {
        $this->artisan('cloud:usage', [
            '--provider' => 'aws'
        ])
        ->expectsOutput('📊 Cloud Usage Statistics')
        ->expectsOutput('📈 aws Usage Statistics')
        ->assertExitCode(0);
    }
    
    public function testCloudUsageAllCommand()
    {
        $this->artisan('cloud:usage', [
            '--all' => true
        ])
        ->expectsOutput('📊 Cloud Usage Statistics')
        ->expectsOutput('🔍 Gathering usage data from all providers...')
        ->assertExitCode(0);
    }
    
    public function testCloudUsageJsonCommand()
    {
        $this->artisan('cloud:usage', [
            '--provider' => 'aws',
            '--format' => 'json'
        ])
        ->assertExitCode(0);
    }
}
```

## 🎯 Feature Testing

### Testing File Upload Feature

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

class UploadTest extends TestCase
{
    public function testFileUploadFeature()
    {
        $file = UploadedFile::fake()->image('photo.jpg', 800, 600);
        
        $response = $this->postJson('/api/multicloud/upload', [
            'file' => $file,
            'path' => 'images/photo.jpg',
            'provider' => 'aws'
        ]);
        
        $response->assertStatus(200);
        
        $data = $response->json();
        $this->assertEquals('success', $data['status']);
        $this->assertEquals('images/photo.jpg', $data['path']);
        $this->assertEquals('aws', $data['provider']);
    }
    
    public function testFileUploadWithOptions()
    {
        $file = UploadedFile::fake()->image('photo.jpg');
        
        $response = $this->postJson('/api/multicloud/upload', [
            'file' => $file,
            'path' => 'images/photo.jpg',
            'provider' => 'aws',
            'options' => [
                'ACL' => 'public-read',
                'CacheControl' => 'max-age=31536000'
            ]
        ]);
        
        $response->assertStatus(200);
        
        $data = $response->json();
        $this->assertEquals('success', $data['status']);
    }
    
    public function testFileUploadValidation()
    {
        $response = $this->postJson('/api/multicloud/upload', [
            'path' => 'images/photo.jpg',
            'provider' => 'aws'
        ]);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['file']);
    }
    
    public function testFileUploadInvalidProvider()
    {
        $file = UploadedFile::fake()->image('photo.jpg');
        
        $response = $this->postJson('/api/multicloud/upload', [
            'file' => $file,
            'path' => 'images/photo.jpg',
            'provider' => 'invalid-provider'
        ]);
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['provider']);
    }
}
```

### Testing File Download Feature

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class DownloadTest extends TestCase
{
    public function testFileDownloadFeature()
    {
        $response = $this->getJson('/api/multicloud/download?path=images/photo.jpg&provider=aws');
        
        $response->assertStatus(200);
    }
    
    public function testFileDownloadNotFound()
    {
        $response = $this->getJson('/api/multicloud/download?path=nonexistent/file.jpg&provider=aws');
        
        $response->assertStatus(400);
    }
    
    public function testFileDownloadValidation()
    {
        $response = $this->getJson('/api/multicloud/download?provider=aws');
        
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['path']);
    }
}
```

### Testing Usage Feature

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class UsageTest extends TestCase
{
    public function testUsageFeature()
    {
        $response = $this->getJson('/api/multicloud/usage?provider=aws');
        
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'provider' => 'aws'
                 ])
                 ->assertJsonStructure([
                     'status',
                     'provider',
                     'storage',
                     'requests',
                     'costs'
                 ]);
    }
    
    public function testUsageAllProviders()
    {
        $response = $this->getJson('/api/multicloud/usage');
        
        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success'
                 ]);
    }
}
```

## 🔧 Mock Testing

### Mocking Cloud Providers

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;
use Mockery;

class MockTest extends TestCase
{
    public function testMockedProvider()
    {
        $mockProvider = Mockery::mock('Subhashladumor\LaravelMulticloud\Contracts\CloudProviderInterface');
        
        $mockProvider->shouldReceive('upload')
                    ->once()
                    ->with('test/file.txt', 'Hello World!', [])
                    ->andReturn([
                        'status' => 'success',
                        'path' => 'test/file.txt',
                        'provider' => 'aws'
                    ]);
        
        $this->app->instance('multicloud', $mockProvider);
        
        $result = LaravelMulticloud::upload('test/file.txt', 'Hello World!');
        
        $this->assertEquals('success', $result['status']);
    }
    
    public function testMockedUpload()
    {
        $mockProvider = Mockery::mock('Subhashladumor\LaravelMulticloud\Contracts\CloudProviderInterface');
        
        $mockProvider->shouldReceive('upload')
                    ->once()
                    ->andReturn([
                        'status' => 'success',
                        'path' => 'test/file.txt',
                        'provider' => 'aws'
                    ]);
        
        $this->app->instance('multicloud', $mockProvider);
        
        $result = LaravelMulticloud::upload('test/file.txt', 'Hello World!');
        
        $this->assertEquals('success', $result['status']);
    }
}
```

## 📊 Test Data

### Creating Test Files

```php
<?php

namespace Tests\Support;

use Illuminate\Http\UploadedFile;

class TestDataFactory
{
    public static function createImageFile($name = 'test.jpg', $width = 800, $height = 600)
    {
        return UploadedFile::fake()->image($name, $width, $height);
    }
    
    public static function createTextFile($name = 'test.txt', $content = 'Hello World!')
    {
        return UploadedFile::fake()->create($name, 100, 'text/plain', $content);
    }
    
    public static function createPdfFile($name = 'test.pdf')
    {
        return UploadedFile::fake()->create($name, 100, 'application/pdf');
    }
    
    public static function createVideoFile($name = 'test.mp4')
    {
        return UploadedFile::fake()->create($name, 100, 'video/mp4');
    }
}
```

### Test Configuration

```php
<?php

namespace Tests\Support;

class TestConfig
{
    public static function getAwsConfig()
    {
        return [
            'key' => 'test-key',
            'secret' => 'test-secret',
            'region' => 'us-east-1',
            'bucket' => 'test-bucket'
        ];
    }
    
    public static function getAzureConfig()
    {
        return [
            'account_name' => 'test-account',
            'account_key' => 'test-key',
            'container' => 'test-container'
        ];
    }
    
    public static function getGcpConfig()
    {
        return [
            'project_id' => 'test-project',
            'bucket' => 'test-bucket',
            'key_file' => 'test-key-file'
        ];
    }
}
```

## 🎯 Best Practices

### Test Organization

1. **Group related tests** in the same test class
2. **Use descriptive test names** that explain what is being tested
3. **Keep tests focused** on a single behavior
4. **Use setUp() and tearDown()** for common setup and cleanup

### Test Data Management

1. **Use factories** for creating test data
2. **Clean up test data** after each test
3. **Use realistic test data** that matches production scenarios
4. **Avoid hardcoded values** in tests

### Mocking Strategy

1. **Mock external dependencies** to isolate units under test
2. **Use partial mocks** when you need some real behavior
3. **Verify mock interactions** to ensure correct usage
4. **Reset mocks** between tests

### Assertion Best Practices

1. **Use specific assertions** rather than generic ones
2. **Test both success and failure scenarios**
3. **Verify all important aspects** of the response
4. **Use meaningful assertion messages**

## 📚 Next Steps

1. **Check [Examples & Tutorials](examples.md)** for practical usage patterns
2. **Explore [Cloud Providers](cloud-providers.md)** for provider-specific features
3. **Read [API Reference](api-reference.md)** for complete method documentation
4. **Learn about [HTTP API](http-api.md)** for web integration

---

**Ready to test?** Check out [Examples & Tutorials](examples.md) for practical usage patterns!

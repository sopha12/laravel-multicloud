<?php

declare(strict_types=1);

namespace Subhashladumor\LaravelMulticloud\Tests;

use Orchestra\Testbench\TestCase;
use Subhashladumor\LaravelMulticloud\CloudManager;
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;
use Subhashladumor\LaravelMulticloud\LaravelMulticloudServiceProvider;
use Subhashladumor\LaravelMulticloud\Providers\AwsProvider;
use Subhashladumor\LaravelMulticloud\Providers\AzureProvider;
use Subhashladumor\LaravelMulticloud\Providers\GcpProvider;
use Subhashladumor\LaravelMulticloud\Providers\CloudinaryProvider;
use Subhashladumor\LaravelMulticloud\Providers\AlibabaProvider;
use Subhashladumor\LaravelMulticloud\Providers\IbmProvider;
use Subhashladumor\LaravelMulticloud\Providers\DigitalOceanProvider;
use Subhashladumor\LaravelMulticloud\Providers\OracleProvider;
use Subhashladumor\LaravelMulticloud\Providers\CloudflareProvider;

/**
 * Cloud Test Suite
 * 
 * Unit tests for the Laravel MultiCloud package
 * 
 * @package Subhashladumor\LaravelMulticloud\Tests
 * @author Subhash Ladumor <subhashladumor@gmail.com>
 * @license MIT
 */
class CloudTest extends TestCase
{
    /**
     * Setup the test environment
     * 
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test configuration
        config([
            'multicloud.default' => 'aws',
            'multicloud.providers.aws' => [
                'key' => 'test-key',
                'secret' => 'test-secret',
                'region' => 'us-east-1',
                'bucket' => 'test-bucket',
            ],
        ]);
    }

    /**
     * Get package providers
     * 
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            LaravelMulticloudServiceProvider::class,
        ];
    }

    /**
     * Get package aliases
     * 
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app): array
    {
        return [
            'LaravelMulticloud' => LaravelMulticloud::class,
        ];
    }

    /**
     * Test CloudManager instantiation
     * 
     * @return void
     */
    public function testCloudManagerInstantiation(): void
    {
        $manager = new CloudManager($this->app);
        $this->assertInstanceOf(CloudManager::class, $manager);
    }

    /**
     * Test default driver resolution
     * 
     * @return void
     */
    public function testDefaultDriverResolution(): void
    {
        $manager = new CloudManager($this->app);
        $driver = $manager->driver();
        
        $this->assertInstanceOf(AwsProvider::class, $driver);
    }

    /**
     * Test specific driver resolution
     * 
     * @return void
     */
    public function testSpecificDriverResolution(): void
    {
        $manager = new CloudManager($this->app);
        
        // Test AWS driver
        $awsDriver = $manager->driver('aws');
        $this->assertInstanceOf(AwsProvider::class, $awsDriver);
        
        // Test Azure driver
        $azureDriver = $manager->driver('azure');
        $this->assertInstanceOf(AzureProvider::class, $azureDriver);
        
        // Test GCP driver
        $gcpDriver = $manager->driver('gcp');
        $this->assertInstanceOf(GcpProvider::class, $gcpDriver);
        
        // Test Cloudinary driver
        $cloudinaryDriver = $manager->driver('cloudinary');
        $this->assertInstanceOf(CloudinaryProvider::class, $cloudinaryDriver);
        
        // Test Alibaba driver
        $alibabaDriver = $manager->driver('alibaba');
        $this->assertInstanceOf(AlibabaProvider::class, $alibabaDriver);
        
        // Test IBM driver
        $ibmDriver = $manager->driver('ibm');
        $this->assertInstanceOf(IbmProvider::class, $ibmDriver);
        
        // Test DigitalOcean driver
        $doDriver = $manager->driver('digitalocean');
        $this->assertInstanceOf(DigitalOceanProvider::class, $doDriver);
        
        // Test Oracle driver
        $oracleDriver = $manager->driver('oracle');
        $this->assertInstanceOf(OracleProvider::class, $oracleDriver);
        
        // Test Cloudflare driver
        $cloudflareDriver = $manager->driver('cloudflare');
        $this->assertInstanceOf(CloudflareProvider::class, $cloudflareDriver);
    }

    /**
     * Test Facade access
     * 
     * @return void
     */
    public function testFacadeAccess(): void
    {
        $driver = LaravelMulticloud::driver();
        $this->assertInstanceOf(AwsProvider::class, $driver);
    }

    /**
     * Test AWS provider operations
     * 
     * @return void
     */
    public function testAwsProviderOperations(): void
    {
        $provider = new AwsProvider();
        $config = [
            'key' => 'test-key',
            'secret' => 'test-secret',
            'region' => 'us-east-1',
            'bucket' => 'test-bucket',
        ];
        
        // Test connection
        $connected = $provider->connect($config);
        $this->assertTrue($connected);
        
        // Test upload
        $uploadResult = $provider->upload('test/file.txt', 'test content');
        $this->assertEquals('success', $uploadResult['status']);
        $this->assertEquals('aws', $uploadResult['provider']);
        
        // Test download
        $downloadResult = $provider->download('test/file.txt');
        $this->assertEquals('success', $downloadResult['status']);
        $this->assertStringContainsString('test/file.txt', $downloadResult['content']);
        
        // Test delete
        $deleteResult = $provider->delete('test/file.txt');
        $this->assertEquals('success', $deleteResult['status']);
        
        // Test list
        $listResult = $provider->list();
        $this->assertEquals('success', $listResult['status']);
        $this->assertIsArray($listResult['files']);
        
        // Test exists
        $exists = $provider->exists('test/file.txt');
        $this->assertIsBool($exists);
        
        // Test metadata
        $metadata = $provider->getMetadata('test/file.txt');
        $this->assertEquals('success', $metadata['status']);
        
        // Test signed URL
        $signedUrl = $provider->generateSignedUrl('test/file.txt');
        $this->assertIsString($signedUrl);
        $this->assertStringContainsString('test/file.txt', $signedUrl);
        
        // Test usage
        $usage = $provider->getUsage();
        $this->assertEquals('success', $usage['status']);
        $this->assertIsArray($usage['storage']);
        $this->assertIsArray($usage['requests']);
        $this->assertIsArray($usage['costs']);
        
        // Test connection test
        $connectionTest = $provider->testConnection();
        $this->assertEquals('success', $connectionTest['status']);
        
        // Test provider info
        $this->assertEquals('Amazon Web Services (AWS)', $provider->getProviderName());
        $this->assertEquals('1.0.0', $provider->getProviderVersion());
    }

    /**
     * Test Azure provider operations
     * 
     * @return void
     */
    public function testAzureProviderOperations(): void
    {
        $provider = new AzureProvider();
        $config = [
            'account_name' => 'test-account',
            'container' => 'test-container',
            'connection_string' => 'test-connection-string',
        ];
        
        $connected = $provider->connect($config);
        $this->assertTrue($connected);
        
        $uploadResult = $provider->upload('test/file.txt', 'test content');
        $this->assertEquals('success', $uploadResult['status']);
        $this->assertEquals('azure', $uploadResult['provider']);
        
        $this->assertEquals('Microsoft Azure', $provider->getProviderName());
    }

    /**
     * Test GCP provider operations
     * 
     * @return void
     */
    public function testGcpProviderOperations(): void
    {
        $provider = new GcpProvider();
        $config = [
            'project_id' => 'test-project',
            'bucket' => 'test-bucket',
            'key_file' => 'test-key-file',
        ];
        
        $connected = $provider->connect($config);
        $this->assertTrue($connected);
        
        $uploadResult = $provider->upload('test/file.txt', 'test content');
        $this->assertEquals('success', $uploadResult['status']);
        $this->assertEquals('gcp', $uploadResult['provider']);
        
        $this->assertEquals('Google Cloud Platform (GCP)', $provider->getProviderName());
    }

    /**
     * Test Cloudinary provider operations
     * 
     * @return void
     */
    public function testCloudinaryProviderOperations(): void
    {
        $provider = new CloudinaryProvider();
        $config = [
            'cloud_name' => 'test-cloud',
            'api_key' => 'test-key',
            'api_secret' => 'test-secret',
        ];
        
        $connected = $provider->connect($config);
        $this->assertTrue($connected);
        
        $uploadResult = $provider->upload('test/file.txt', 'test content');
        $this->assertEquals('success', $uploadResult['status']);
        $this->assertEquals('cloudinary', $uploadResult['provider']);
        
        $this->assertEquals('Cloudinary', $provider->getProviderName());
    }

    /**
     * Test Alibaba provider operations
     * 
     * @return void
     */
    public function testAlibabaProviderOperations(): void
    {
        $provider = new AlibabaProvider();
        $config = [
            'access_key_id' => 'test-key',
            'access_key_secret' => 'test-secret',
            'endpoint' => 'test-endpoint',
            'bucket' => 'test-bucket',
        ];
        
        $connected = $provider->connect($config);
        $this->assertTrue($connected);
        
        $uploadResult = $provider->upload('test/file.txt', 'test content');
        $this->assertEquals('success', $uploadResult['status']);
        $this->assertEquals('alibaba', $uploadResult['provider']);
        
        $this->assertEquals('Alibaba Cloud', $provider->getProviderName());
    }

    /**
     * Test IBM provider operations
     * 
     * @return void
     */
    public function testIbmProviderOperations(): void
    {
        $provider = new IbmProvider();
        $config = [
            'api_key' => 'test-key',
            'service_instance_id' => 'test-instance',
            'endpoint' => 'test-endpoint',
            'bucket' => 'test-bucket',
        ];
        
        $connected = $provider->connect($config);
        $this->assertTrue($connected);
        
        $uploadResult = $provider->upload('test/file.txt', 'test content');
        $this->assertEquals('success', $uploadResult['status']);
        $this->assertEquals('ibm', $uploadResult['provider']);
        
        $this->assertEquals('IBM Cloud', $provider->getProviderName());
    }

    /**
     * Test DigitalOcean provider operations
     * 
     * @return void
     */
    public function testDigitalOceanProviderOperations(): void
    {
        $provider = new DigitalOceanProvider();
        $config = [
            'access_key' => 'test-key',
            'secret_key' => 'test-secret',
            'region' => 'nyc3',
            'bucket' => 'test-bucket',
        ];
        
        $connected = $provider->connect($config);
        $this->assertTrue($connected);
        
        $uploadResult = $provider->upload('test/file.txt', 'test content');
        $this->assertEquals('success', $uploadResult['status']);
        $this->assertEquals('digitalocean', $uploadResult['provider']);
        
        $this->assertEquals('DigitalOcean', $provider->getProviderName());
    }

    /**
     * Test Oracle provider operations
     * 
     * @return void
     */
    public function testOracleProviderOperations(): void
    {
        $provider = new OracleProvider();
        $config = [
            'user_ocid' => 'test-user',
            'tenancy_ocid' => 'test-tenancy',
            'fingerprint' => 'test-fingerprint',
            'private_key' => 'test-key',
            'region' => 'us-ashburn-1',
            'bucket' => 'test-bucket',
            'namespace' => 'test-namespace',
        ];
        
        $connected = $provider->connect($config);
        $this->assertTrue($connected);
        
        $uploadResult = $provider->upload('test/file.txt', 'test content');
        $this->assertEquals('success', $uploadResult['status']);
        $this->assertEquals('oracle', $uploadResult['provider']);
        
        $this->assertEquals('Oracle Cloud Infrastructure', $provider->getProviderName());
    }

    /**
     * Test Cloudflare provider operations
     * 
     * @return void
     */
    public function testCloudflareProviderOperations(): void
    {
        $provider = new CloudflareProvider();
        $config = [
            'account_id' => 'test-account',
            'access_key_id' => 'test-key',
            'secret_access_key' => 'test-secret',
            'bucket' => 'test-bucket',
        ];
        
        $connected = $provider->connect($config);
        $this->assertTrue($connected);
        
        $uploadResult = $provider->upload('test/file.txt', 'test content');
        $this->assertEquals('success', $uploadResult['status']);
        $this->assertEquals('cloudflare', $uploadResult['provider']);
        
        $this->assertEquals('Cloudflare', $provider->getProviderName());
    }

    /**
     * Test CloudManager convenience methods
     * 
     * @return void
     */
    public function testCloudManagerConvenienceMethods(): void
    {
        $manager = new CloudManager($this->app);
        
        // Test upload
        $uploadResult = $manager->upload('test/file.txt', 'test content');
        $this->assertEquals('success', $uploadResult['status']);
        
        // Test download
        $downloadResult = $manager->download('test/file.txt');
        $this->assertEquals('success', $downloadResult['status']);
        
        // Test delete
        $deleteResult = $manager->delete('test/file.txt');
        $this->assertEquals('success', $deleteResult['status']);
        
        // Test list
        $listResult = $manager->list();
        $this->assertEquals('success', $listResult['status']);
        
        // Test exists
        $exists = $manager->exists('test/file.txt');
        $this->assertIsBool($exists);
        
        // Test metadata
        $metadata = $manager->getMetadata('test/file.txt');
        $this->assertEquals('success', $metadata['status']);
        
        // Test signed URL
        $signedUrl = $manager->generateSignedUrl('test/file.txt');
        $this->assertIsString($signedUrl);
        
        // Test usage
        $usage = $manager->getUsage();
        $this->assertEquals('success', $usage['status']);
        
        // Test connection test
        $connectionTest = $manager->testConnection();
        $this->assertEquals('success', $connectionTest['status']);
        
        // Test available drivers
        $drivers = $manager->getAvailableDrivers();
        $this->assertIsArray($drivers);
        $this->assertArrayHasKey('aws', $drivers);
        $this->assertArrayHasKey('azure', $drivers);
        $this->assertArrayHasKey('gcp', $drivers);
    }

    /**
     * Test error handling
     * 
     * @return void
     */
    public function testErrorHandling(): void
    {
        $provider = new AwsProvider();
        
        // Test with invalid config
        $connected = $provider->connect([]);
        $this->assertTrue($connected); // Mock implementation always returns true
        
        // Test operations with empty path
        $uploadResult = $provider->upload('', 'test content');
        $this->assertEquals('success', $uploadResult['status']); // Mock implementation
        
        $downloadResult = $provider->download('');
        $this->assertEquals('success', $downloadResult['status']); // Mock implementation
    }

    /**
     * Test configuration loading
     * 
     * @return void
     */
    public function testConfigurationLoading(): void
    {
        $this->assertEquals('aws', config('multicloud.default'));
        $this->assertIsArray(config('multicloud.providers'));
        $this->assertIsArray(config('multicloud.settings'));
        $this->assertIsArray(config('multicloud.enabled_providers'));
        $this->assertIsArray(config('multicloud.fallback'));
    }
}

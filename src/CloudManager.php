<?php

declare(strict_types=1);

namespace Subhashladumor\LaravelMulticloud;

use Illuminate\Support\Manager;
use Subhashladumor\LaravelMulticloud\Contracts\CloudProviderInterface;
use Subhashladumor\LaravelMulticloud\Providers\AwsProvider;
use Subhashladumor\LaravelMulticloud\Providers\AzureProvider;
use Subhashladumor\LaravelMulticloud\Providers\GcpProvider;
use Subhashladumor\LaravelMulticloud\Providers\CloudinaryProvider;
use Subhashladumor\LaravelMulticloud\Providers\AlibabaProvider;
use Subhashladumor\LaravelMulticloud\Providers\CloudflareProvider;

/**
 * Cloud Manager
 * 
 * Main router that dynamically loads cloud drivers and forwards method calls
 * to the appropriate cloud provider implementation.
 * 
 * @package Subhashladumor\LaravelMulticloud
 * @author Subhash Ladumor <subhashladumor@gmail.com>
 * @license MIT
 */
class CloudManager extends Manager
{
    /**
     * Get the default driver name
     * 
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->config->get('multicloud.default', 'aws');
    }

    /**
     * Create AWS provider instance
     * 
     * @return CloudProviderInterface
     */
    protected function createAwsDriver(): CloudProviderInterface
    {
        $config = $this->config->get('multicloud.providers.aws', []);
        $provider = new AwsProvider();
        $provider->connect($config);
        
        return $provider;
    }

    /**
     * Create Azure provider instance
     * 
     * @return CloudProviderInterface
     */
    protected function createAzureDriver(): CloudProviderInterface
    {
        $config = $this->config->get('multicloud.providers.azure', []);
        $provider = new AzureProvider();
        $provider->connect($config);
        
        return $provider;
    }

    /**
     * Create GCP provider instance
     * 
     * @return CloudProviderInterface
     */
    protected function createGcpDriver(): CloudProviderInterface
    {
        $config = $this->config->get('multicloud.providers.gcp', []);
        $provider = new GcpProvider();
        $provider->connect($config);
        
        return $provider;
    }

    /**
     * Create Cloudinary provider instance
     * 
     * @return CloudProviderInterface
     */
    protected function createCloudinaryDriver(): CloudProviderInterface
    {
        $config = $this->config->get('multicloud.providers.cloudinary', []);
        $provider = new CloudinaryProvider();
        $provider->connect($config);
        
        return $provider;
    }

    /**
     * Create Alibaba provider instance
     * 
     * @return CloudProviderInterface
     */
    protected function createAlibabaDriver(): CloudProviderInterface
    {
        $config = $this->config->get('multicloud.providers.alibaba', []);
        $provider = new AlibabaProvider();
        $provider->connect($config);
        
        return $provider;
    }

    /**
     * Create Cloudflare provider instance
     * 
     * @return CloudProviderInterface
     */
    protected function createCloudflareDriver(): CloudProviderInterface
    {
        $config = $this->config->get('multicloud.providers.cloudflare', []);
        $provider = new CloudflareProvider();
        $provider->connect($config);
        
        return $provider;
    }

    /**
     * Upload a file using the default driver
     * 
     * @param string $path Remote file path
     * @param mixed $file File content or resource
     * @param array $options Additional options
     * @return array Upload result
     */
    public function upload(string $path, $file, array $options = []): array
    {
        return $this->driver()->upload($path, $file, $options);
    }

    /**
     * Download a file using the default driver
     * 
     * @param string $path Remote file path
     * @param string|null $localPath Local file path to save
     * @return array Download result
     */
    public function download(string $path, ?string $localPath = null): array
    {
        return $this->driver()->download($path, $localPath);
    }

    /**
     * Delete a file using the default driver
     * 
     * @param string $path Remote file path
     * @return array Delete result
     */
    public function delete(string $path): array
    {
        return $this->driver()->delete($path);
    }

    /**
     * List files using the default driver
     * 
     * @param string $path Directory path
     * @param array $options Additional options
     * @return array List of files
     */
    public function list(string $path = '', array $options = []): array
    {
        return $this->driver()->list($path, $options);
    }

    /**
     * Check if file exists using the default driver
     * 
     * @param string $path Remote file path
     * @return bool True if file exists
     */
    public function exists(string $path): bool
    {
        return $this->driver()->exists($path);
    }

    /**
     * Get file metadata using the default driver
     * 
     * @param string $path Remote file path
     * @return array File metadata
     */
    public function getMetadata(string $path): array
    {
        return $this->driver()->getMetadata($path);
    }

    /**
     * Generate signed URL using the default driver
     * 
     * @param string $path Remote file path
     * @param int $expiration Expiration time in seconds
     * @return string Signed URL
     */
    public function generateSignedUrl(string $path, int $expiration = 3600): string
    {
        return $this->driver()->generateSignedUrl($path, $expiration);
    }

    /**
     * Get usage statistics using the default driver
     * 
     * @return array Usage statistics
     */
    public function getUsage(): array
    {
        return $this->driver()->getUsage();
    }

    /**
     * Test connection using the default driver
     * 
     * @return array Test result
     */
    public function testConnection(): array
    {
        return $this->driver()->testConnection();
    }

    /**
     * Get all available drivers
     * 
     * @return array List of available drivers
     */
    public function getAvailableDrivers(): array
    {
        return [
            'aws' => 'Amazon Web Services',
            'azure' => 'Microsoft Azure',
            'gcp' => 'Google Cloud Platform',
            'cloudinary' => 'Cloudinary',
            'alibaba' => 'Alibaba Cloud',
            'cloudflare' => 'Cloudflare',
        ];
    }
}

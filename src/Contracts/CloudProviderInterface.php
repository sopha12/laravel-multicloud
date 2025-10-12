<?php

declare(strict_types=1);

namespace Subhashladumor\LaravelMulticloud\Contracts;

/**
 * Cloud Provider Interface
 * 
 * Defines the standard methods that every cloud provider must implement
 * to ensure consistent API across all cloud services.
 * 
 * @package Subhashladumor\LaravelMulticloud\Contracts
 * @author Subhash Ladumor <subhashladumor@gmail.com>
 * @license MIT
 */
interface CloudProviderInterface
{
    /**
     * Connect to the cloud provider
     * 
     * @param array $config Configuration array
     * @return bool True if connection successful
     */
    public function connect(array $config): bool;

    /**
     * Upload a file to the cloud storage
     * 
     * @param string $path Remote file path
     * @param mixed $file File content or resource
     * @param array $options Additional options
     * @return array Upload result with status and metadata
     */
    public function upload(string $path, $file, array $options = []): array;

    /**
     * Download a file from the cloud storage
     * 
     * @param string $path Remote file path
     * @param string|null $localPath Local file path to save
     * @return array Download result with status and content
     */
    public function download(string $path, ?string $localPath = null): array;

    /**
     * Delete a file from the cloud storage
     * 
     * @param string $path Remote file path
     * @return array Delete result with status
     */
    public function delete(string $path): array;

    /**
     * List files in a directory
     * 
     * @param string $path Directory path
     * @param array $options Additional options
     * @return array List of files with metadata
     */
    public function list(string $path = '', array $options = []): array;

    /**
     * Check if a file exists
     * 
     * @param string $path Remote file path
     * @return bool True if file exists
     */
    public function exists(string $path): bool;

    /**
     * Get file metadata
     * 
     * @param string $path Remote file path
     * @return array File metadata
     */
    public function getMetadata(string $path): array;

    /**
     * Generate a signed URL for temporary access
     * 
     * @param string $path Remote file path
     * @param int $expiration Expiration time in seconds
     * @return string Signed URL
     */
    public function generateSignedUrl(string $path, int $expiration = 3600): string;

    /**
     * Get storage usage statistics
     * 
     * @return array Usage statistics
     */
    public function getUsage(): array;

    /**
     * Get provider name
     * 
     * @return string Provider name
     */
    public function getProviderName(): string;

    /**
     * Get provider version
     * 
     * @return string Provider version
     */
    public function getProviderVersion(): string;

    /**
     * Test the connection to the cloud provider
     * 
     * @return array Test result with status and details
     */
    public function testConnection(): array;
}

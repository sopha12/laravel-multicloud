<?php

declare(strict_types=1);

namespace Subhashladumor\LaravelMulticloud\Facades;

use Illuminate\Support\Facades\Facade;
use Subhashladumor\LaravelMulticloud\CloudManager;

/**
 * Laravel MultiCloud Facade
 * 
 * Provides static access to the CloudManager instance
 * 
 * @package Subhashladumor\LaravelMulticloud\Facades
 * @author Subhash Ladumor <subhashladumor@gmail.com>
 * @license MIT
 * 
 * @method static \Subhashladumor\LaravelMulticloud\Contracts\CloudProviderInterface driver(string $driver = null)
 * @method static array upload(string $path, mixed $file, array $options = [])
 * @method static array download(string $path, string $localPath = null)
 * @method static array delete(string $path)
 * @method static array list(string $path = '', array $options = [])
 * @method static bool exists(string $path)
 * @method static array getMetadata(string $path)
 * @method static string generateSignedUrl(string $path, int $expiration = 3600)
 * @method static array getUsage()
 * @method static array testConnection()
 * @method static array getAvailableDrivers()
 */
class LaravelMulticloud extends Facade
{
    /**
     * Get the registered name of the component
     * 
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'multicloud';
    }

    /**
     * Get the CloudManager instance
     * 
     * @return CloudManager
     */
    public static function getFacadeRoot(): CloudManager
    {
        return parent::getFacadeRoot();
    }

    /**
     * Handle dynamic static method calls
     * 
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        $instance = static::getFacadeRoot();

        return $instance->$method(...$parameters);
    }
}

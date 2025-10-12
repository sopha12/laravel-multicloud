<?php

declare(strict_types=1);

/**
 * Laravel MultiCloud Configuration
 * 
 * Configuration file for managing multiple cloud providers
 * 
 * @package Subhashladumor\LaravelMulticloud
 * @author Subhash Ladumor <subhashladumor@gmail.com>
 * @license MIT
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Default Cloud Provider
    |--------------------------------------------------------------------------
    |
    | This option controls the default cloud provider that will be used
    | when no specific provider is specified. Supported providers:
    | aws, azure, gcp, cloudinary, alibaba, ibm, digitalocean, oracle, cloudflare
    |
    */
    'default' => env('MULTICLOUD_DEFAULT', 'aws'),

    /*
    |--------------------------------------------------------------------------
    | Cloud Providers Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the settings for each cloud provider.
    | Each provider has its own configuration section with the necessary
    | credentials and settings for authentication and operation.
    |
    */
    'providers' => [
        /*
        |--------------------------------------------------------------------------
        | Amazon Web Services (AWS) Configuration
        |--------------------------------------------------------------------------
        */
        'aws' => [
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'bucket' => env('AWS_BUCKET'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'options' => [
                'ACL' => env('AWS_ACL', 'private'),
                'CacheControl' => env('AWS_CACHE_CONTROL', 'max-age=31536000'),
            ],
        ],

        /*
        |--------------------------------------------------------------------------
        | Microsoft Azure Configuration
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | Google Cloud Platform (GCP) Configuration
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | Cloudinary Configuration
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | Alibaba Cloud Configuration
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | IBM Cloud Configuration
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | DigitalOcean Configuration
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | Oracle Cloud Infrastructure Configuration
        |--------------------------------------------------------------------------
        */
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

        /*
        |--------------------------------------------------------------------------
        | Cloudflare Configuration
        |--------------------------------------------------------------------------
        */
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
    ],

    /*
    |--------------------------------------------------------------------------
    | Global Settings
    |--------------------------------------------------------------------------
    |
    | Global settings that apply to all cloud providers
    |
    */
    'settings' => [
        /*
        |--------------------------------------------------------------------------
        | Default File Options
        |--------------------------------------------------------------------------
        */
        'default_options' => [
            'visibility' => 'private',
            'cache_control' => 'max-age=31536000',
            'metadata' => [],
        ],

        /*
        |--------------------------------------------------------------------------
        | Upload Settings
        |--------------------------------------------------------------------------
        */
        'upload' => [
            'max_file_size' => env('MULTICLOUD_MAX_FILE_SIZE', 10485760), // 10MB
            'allowed_extensions' => [
                'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp',
                'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
                'txt', 'csv', 'json', 'xml', 'zip', 'rar',
                'mp4', 'avi', 'mov', 'wmv', 'flv', 'webm',
                'mp3', 'wav', 'flac', 'aac', 'ogg',
            ],
            'auto_generate_thumbnails' => env('MULTICLOUD_AUTO_THUMBNAILS', false),
        ],

        /*
        |--------------------------------------------------------------------------
        | Cache Settings
        |--------------------------------------------------------------------------
        */
        'cache' => [
            'enabled' => env('MULTICLOUD_CACHE_ENABLED', true),
            'ttl' => env('MULTICLOUD_CACHE_TTL', 3600), // 1 hour
            'prefix' => env('MULTICLOUD_CACHE_PREFIX', 'multicloud'),
        ],

        /*
        |--------------------------------------------------------------------------
        | Logging Settings
        |--------------------------------------------------------------------------
        */
        'logging' => [
            'enabled' => env('MULTICLOUD_LOGGING_ENABLED', true),
            'level' => env('MULTICLOUD_LOG_LEVEL', 'info'),
            'channel' => env('MULTICLOUD_LOG_CHANNEL', 'daily'),
        ],

        /*
        |--------------------------------------------------------------------------
        | Security Settings
        |--------------------------------------------------------------------------
        */
        'security' => [
            'encrypt_uploads' => env('MULTICLOUD_ENCRYPT_UPLOADS', false),
            'encryption_key' => env('MULTICLOUD_ENCRYPTION_KEY'),
            'require_authentication' => env('MULTICLOUD_REQUIRE_AUTH', false),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Provider Status
    |--------------------------------------------------------------------------
    |
    | Enable or disable specific cloud providers
    |
    */
    'enabled_providers' => [
        'aws' => env('MULTICLOUD_AWS_ENABLED', true),
        'azure' => env('MULTICLOUD_AZURE_ENABLED', true),
        'gcp' => env('MULTICLOUD_GCP_ENABLED', true),
        'cloudinary' => env('MULTICLOUD_CLOUDINARY_ENABLED', true),
        'alibaba' => env('MULTICLOUD_ALIBABA_ENABLED', true),
        'ibm' => env('MULTICLOUD_IBM_ENABLED', true),
        'digitalocean' => env('MULTICLOUD_DIGITALOCEAN_ENABLED', true),
        'oracle' => env('MULTICLOUD_ORACLE_ENABLED', true),
        'cloudflare' => env('MULTICLOUD_CLOUDFLARE_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Fallback Settings
    |--------------------------------------------------------------------------
    |
    | Configure fallback behavior when primary provider fails
    |
    */
    'fallback' => [
        'enabled' => env('MULTICLOUD_FALLBACK_ENABLED', true),
        'providers' => [
            'aws' => ['azure', 'gcp'],
            'azure' => ['aws', 'gcp'],
            'gcp' => ['aws', 'azure'],
            'cloudinary' => ['aws', 'azure'],
            'alibaba' => ['aws', 'azure'],
            'ibm' => ['aws', 'azure'],
            'digitalocean' => ['aws', 'azure'],
            'oracle' => ['aws', 'azure'],
            'cloudflare' => ['aws', 'azure'],
        ],
        'max_retries' => env('MULTICLOUD_MAX_RETRIES', 3),
        'retry_delay' => env('MULTICLOUD_RETRY_DELAY', 1000), // milliseconds
    ],
];

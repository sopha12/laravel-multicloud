# 🎯 Artisan Commands

Laravel MultiCloud provides powerful Artisan commands for deployment and monitoring operations.

## 📋 Available Commands

| Command | Description | Options |
|---------|-------------|---------|
| `cloud:deploy` | Deploy application to cloud provider | `--provider`, `--environment`, `--region`, `--dry-run` |
| `cloud:usage` | Display usage statistics | `--provider`, `--format`, `--detailed`, `--all` |

## 🚀 Deployment Command

### Basic Usage

```bash
# Deploy to default provider
php artisan cloud:deploy

# Deploy to specific provider
php artisan cloud:deploy --provider=aws

# Deploy to specific environment and region
php artisan cloud:deploy --provider=gcp --environment=staging --region=us-central1

# Dry run deployment (simulation only)
php artisan cloud:deploy --provider=azure --dry-run
```

### Command Options

#### `--provider`

Specify the cloud provider to deploy to.

**Available Providers:**
- `aws` - Amazon Web Services
- `azure` - Microsoft Azure
- `gcp` - Google Cloud Platform
- `cloudinary` - Cloudinary
- `alibaba` - Alibaba Cloud
- `ibm` - IBM Cloud
- `digitalocean` - DigitalOcean
- `oracle` - Oracle Cloud
- `cloudflare` - Cloudflare

**Example:**
```bash
php artisan cloud:deploy --provider=aws
```

#### `--environment`

Set the deployment environment.

**Default:** `production`

**Example:**
```bash
php artisan cloud:deploy --provider=aws --environment=staging
```

#### `--region`

Specify the deployment region.

**Example:**
```bash
php artisan cloud:deploy --provider=aws --region=us-west-2
```

#### `--dry-run`

Perform a simulation without actual deployment.

**Example:**
```bash
php artisan cloud:deploy --provider=aws --dry-run
```

### Deployment Process

The deployment command performs the following steps:

1. **Connection Test** - Verifies cloud provider connectivity
2. **Package Preparation** - Prepares application files
3. **File Upload** - Uploads application files
4. **Environment Configuration** - Sets up environment variables
5. **Load Balancer Setup** - Configures load balancing
6. **Database Migration** - Runs database migrations
7. **Health Checks** - Performs application health checks
8. **DNS Updates** - Updates DNS records
9. **Cleanup** - Removes old deployment files

### Example Output

```bash
$ php artisan cloud:deploy --provider=aws --environment=staging

🚀 Starting deployment to aws...
🔍 Testing connection to aws...
✅ Connection successful!

Preparing deployment package...
Uploading application files...
Configuring environment variables...
Setting up load balancer...
Deploying database migrations...
Running health checks...
Updating DNS records...
Cleaning up old deployments...

📊 Deployment Summary
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
| Property    | Value        |
|-------------|--------------|
| Provider    | aws          |
| Environment | staging      |
| Region      | Default      |
| Mode        | Live         |
| Status      | Completed    |
| Timestamp   | 2024-01-01 12:00:00 |

🎉 Deployment completed successfully!
🌐 Application is now live on aws

💡 Next steps:
   • Monitor application health
   • Check logs for any issues
   • Run smoke tests
   • Update monitoring dashboards
```

### Error Handling

The deployment command handles various error scenarios:

```bash
$ php artisan cloud:deploy --provider=invalid

❌ Invalid provider: invalid
Available providers: aws, azure, gcp, cloudinary, alibaba, ibm, digitalocean, oracle, cloudflare
```

```bash
$ php artisan cloud:deploy --provider=aws

🚀 Starting deployment to aws...
🔍 Testing connection to aws...
❌ Connection failed: Invalid credentials
```

## 📊 Usage Command

### Basic Usage

```bash
# Show usage for default provider
php artisan cloud:usage

# Show usage for specific provider
php artisan cloud:usage --provider=aws

# Show detailed usage information
php artisan cloud:usage --provider=gcp --detailed

# Show usage for all providers
php artisan cloud:usage --all

# Export usage data
php artisan cloud:usage --provider=azure --format=json
php artisan cloud:usage --all --format=csv
```

### Command Options

#### `--provider`

Specify the cloud provider to check usage for.

**Example:**
```bash
php artisan cloud:usage --provider=aws
```

#### `--format`

Set the output format.

**Available Formats:**
- `table` - Tabular format (default)
- `json` - JSON format
- `csv` - CSV format

**Example:**
```bash
php artisan cloud:usage --provider=aws --format=json
```

#### `--detailed`

Show detailed usage information including breakdowns.

**Example:**
```bash
php artisan cloud:usage --provider=aws --detailed
```

#### `--all`

Show usage for all enabled providers.

**Example:**
```bash
php artisan cloud:usage --all
```

### Usage Information

The usage command displays:

- **Storage Statistics**
  - Total objects
  - Total size (bytes and human-readable)
  - Storage class distribution

- **Request Statistics**
  - Get requests
  - Put requests
  - Delete requests
  - Head requests (if available)

- **Cost Information**
  - Storage costs
  - Request costs
  - Bandwidth costs (if applicable)
  - Total costs

- **Provider-Specific Data**
  - Transformations (Cloudinary)
  - Egress data (Cloudflare)
  - Custom metrics

### Example Output

#### Table Format

```bash
$ php artisan cloud:usage --provider=aws

📊 Cloud Usage Statistics
📈 aws Usage Statistics
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
| Metric          | Value        |
|-----------------|--------------|
| Provider        | aws          |
| Total Objects   | 1,250        |
| Total Size      | 1.00 GB      |
| Get Requests    | 5,000        |
| Put Requests    | 2,500        |
| Delete Requests | 100          |
| Total Cost      | $0.027       |
```

#### Detailed Format

```bash
$ php artisan cloud:usage --provider=aws --detailed

📊 Cloud Usage Statistics
📈 aws Usage Statistics
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
| Metric          | Value        |
|-----------------|--------------|
| Provider        | aws          |
| Total Objects   | 1,250        |
| Total Size      | 1.00 GB      |
| Get Requests    | 5,000        |
| Put Requests    | 2,500        |
| Delete Requests | 100          |
| Total Cost      | $0.027       |

🔍 Detailed Information
| Storage Metric    | Value        |
|-------------------|--------------|
| Total Objects     | 1,250        |
| Total Size (Bytes)| 1,073,741,824|
| Total Size (Human)| 1.00 GB      |

| Request Type      | Count        |
|-------------------|--------------|
| Get Requests      | 5,000        |
| Put Requests      | 2,500        |
| Delete Requests   | 100          |

| Cost Type         | Amount       |
|-------------------|--------------|
| Storage Cost      | $0.023       |
| Request Cost      | $0.004       |
| Total Cost        | $0.027       |
```

#### JSON Format

```bash
$ php artisan cloud:usage --provider=aws --format=json

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
    "timestamp": "2024-01-01T12:00:00Z"
}
```

#### CSV Format

```bash
$ php artisan cloud:usage --provider=aws --format=csv

Provider,Total Objects,Total Size,Get Requests,Put Requests,Delete Requests,Total Cost
aws,1250,1.00 GB,5000,2500,100,$0.027
```

#### All Providers

```bash
$ php artisan cloud:usage --all

📊 Cloud Usage Statistics
🔍 Gathering usage data from all providers...

📈 aws Usage Statistics
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
| Metric          | Value        |
|-----------------|--------------|
| Provider        | aws          |
| Total Objects   | 1,250        |
| Total Size      | 1.00 GB      |
| Get Requests    | 5,000        |
| Put Requests    | 2,500        |
| Delete Requests | 100          |
| Total Cost      | $0.027       |

📈 azure Usage Statistics
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
| Metric          | Value        |
|-----------------|--------------|
| Provider        | azure        |
| Total Objects   | 2,100        |
| Total Size      | 2.00 GB      |
| Get Requests    | 8,000        |
| Put Requests    | 3,500        |
| Delete Requests | 200          |
| Total Cost      | $0.047       |
```

### Error Handling

The usage command handles various error scenarios:

```bash
$ php artisan cloud:usage --provider=invalid

❌ Invalid provider: invalid
Available providers: aws, azure, gcp, cloudinary, alibaba, ibm, digitalocean, oracle, cloudflare
```

```bash
$ php artisan cloud:usage --provider=aws

🔍 Gathering usage data from aws...
❌ Failed to get usage data: Connection timeout
```

## 🔧 Custom Commands

### Creating Custom Commands

You can create custom commands that use Laravel MultiCloud:

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

class BackupToCloud extends Command
{
    protected $signature = 'backup:cloud 
                            {--provider= : Cloud provider to use}
                            {--path= : Path to backup}';

    protected $description = 'Backup local files to cloud storage';

    public function handle()
    {
        $provider = $this->option('provider') ?: config('multicloud.default');
        $path = $this->option('path') ?: storage_path('backups');

        $this->info("Starting backup to {$provider}...");

        $files = glob($path . '/*');
        $bar = $this->output->createProgressBar(count($files));

        foreach ($files as $file) {
            $filename = basename($file);
            $cloudPath = 'backups/' . date('Y-m-d') . '/' . $filename;

            LaravelMulticloud::driver($provider)->upload($cloudPath, file_get_contents($file));
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Backup completed successfully!');
    }
}
```

### Scheduling Commands

You can schedule MultiCloud commands using Laravel's task scheduler:

```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Daily usage report
    $schedule->command('cloud:usage --all --format=json')
             ->daily()
             ->sendOutputTo(storage_path('logs/usage-report.log'));

    // Weekly backup
    $schedule->command('backup:cloud --provider=aws')
             ->weekly()
             ->sundays()
             ->at('02:00');

    // Monthly cleanup
    $schedule->command('cloud:cleanup --provider=aws --days=30')
             ->monthly();
}
```

## 🎯 Best Practices

### Deployment

1. **Always test with dry-run first**
   ```bash
   php artisan cloud:deploy --provider=aws --dry-run
   ```

2. **Use specific environments**
   ```bash
   php artisan cloud:deploy --provider=aws --environment=staging
   ```

3. **Monitor deployment progress**
   ```bash
   php artisan cloud:deploy --provider=aws --verbose
   ```

### Usage Monitoring

1. **Regular usage checks**
   ```bash
   php artisan cloud:usage --all --detailed
   ```

2. **Export usage data**
   ```bash
   php artisan cloud:usage --provider=aws --format=csv > usage.csv
   ```

3. **Schedule automated reports**
   ```bash
   # Add to crontab
   0 9 * * * cd /path/to/project && php artisan cloud:usage --all --format=json
   ```

### Error Handling

1. **Check provider status**
   ```bash
   php artisan cloud:usage --provider=aws
   ```

2. **Test connections**
   ```bash
   php artisan cloud:deploy --provider=aws --dry-run
   ```

3. **Monitor logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

## 📚 Next Steps

1. **Check [HTTP API](http-api.md)** for web integration
2. **Explore [Examples & Tutorials](examples.md)** for practical usage
3. **Read [API Reference](api-reference.md)** for complete method documentation
4. **Learn about [Testing](testing.md)** for testing your implementation

---

**Ready to automate?** Check out [HTTP API](http-api.md) for web integration!

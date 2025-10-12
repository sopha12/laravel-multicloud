<?php

declare(strict_types=1);

namespace Subhashladumor\LaravelMulticloud\Commands;

use Illuminate\Console\Command;
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;

/**
 * Cloud Usage Command
 * 
 * Displays usage statistics for cloud providers
 * 
 * @package Subhashladumor\LaravelMulticloud\Commands
 * @author Subhash Ladumor <subhashladumor@gmail.com>
 * @license MIT
 */
class CloudUsageCommand extends Command
{
    /**
     * The name and signature of the console command
     * 
     * @var string
     */
    protected $signature = 'cloud:usage 
                            {--provider= : The cloud provider to check usage for}
                            {--format=table : Output format (table, json, csv)}
                            {--detailed : Show detailed usage information}
                            {--all : Show usage for all providers}';

    /**
     * The console command description
     * 
     * @var string
     */
    protected $description = 'Display cloud provider usage statistics';

    /**
     * Execute the console command
     * 
     * @return int
     */
    public function handle(): int
    {
        $provider = $this->option('provider');
        $format = $this->option('format');
        $detailed = $this->option('detailed');
        $all = $this->option('all');

        $this->info("📊 Cloud Usage Statistics");
        $this->newLine();

        if ($all) {
            return $this->showAllProvidersUsage($format, $detailed);
        }

        if ($provider) {
            return $this->showProviderUsage($provider, $format, $detailed);
        }

        // Show default provider usage
        $defaultProvider = config('multicloud.default');
        return $this->showProviderUsage($defaultProvider, $format, $detailed);
    }

    /**
     * Show usage for all providers
     * 
     * @param string $format
     * @param bool $detailed
     * @return int
     */
    private function showAllProvidersUsage(string $format, bool $detailed): int
    {
        $availableProviders = LaravelMulticloud::getAvailableDrivers();
        $usageData = [];

        $this->info("🔍 Gathering usage data from all providers...");
        $bar = $this->output->createProgressBar(count($availableProviders));
        $bar->start();

        foreach ($availableProviders as $providerKey => $providerName) {
            try {
                $usage = LaravelMulticloud::driver($providerKey)->getUsage();
                $usageData[$providerKey] = $usage;
            } catch (\Exception $e) {
                $usageData[$providerKey] = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ];
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        return $this->displayUsageData($usageData, $format, $detailed);
    }

    /**
     * Show usage for a specific provider
     * 
     * @param string $provider
     * @param string $format
     * @param bool $detailed
     * @return int
     */
    private function showProviderUsage(string $provider, string $format, bool $detailed): int
    {
        // Validate provider
        $availableProviders = LaravelMulticloud::getAvailableDrivers();
        if (!array_key_exists($provider, $availableProviders)) {
            $this->error("❌ Invalid provider: {$provider}");
            $this->info("Available providers: " . implode(', ', array_keys($availableProviders)));
            return 1;
        }

        $this->info("🔍 Gathering usage data from {$provider}...");

        try {
            $usage = LaravelMulticloud::driver($provider)->getUsage();
            $usageData = [$provider => $usage];
            
            return $this->displayUsageData($usageData, $format, $detailed);
        } catch (\Exception $e) {
            $this->error("❌ Failed to get usage data: {$e->getMessage()}");
            return 1;
        }
    }

    /**
     * Display usage data in the specified format
     * 
     * @param array $usageData
     * @param string $format
     * @param bool $detailed
     * @return int
     */
    private function displayUsageData(array $usageData, string $format, bool $detailed): int
    {
        switch ($format) {
            case 'json':
                return $this->displayJsonFormat($usageData);
            case 'csv':
                return $this->displayCsvFormat($usageData);
            default:
                return $this->displayTableFormat($usageData, $detailed);
        }
    }

    /**
     * Display usage data in table format
     * 
     * @param array $usageData
     * @param bool $detailed
     * @return int
     */
    private function displayTableFormat(array $usageData, bool $detailed): int
    {
        foreach ($usageData as $provider => $data) {
            if ($data['status'] === 'error') {
                $this->error("❌ {$provider}: {$data['message']}");
                continue;
            }

            $this->info("📈 {$provider} Usage Statistics");
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

            // Basic usage table
            $basicData = [
                ['Provider', $data['provider']],
                ['Total Objects', number_format($data['storage']['total_objects'])],
                ['Total Size', $data['storage']['total_size_human']],
                ['Get Requests', number_format($data['requests']['get_requests'])],
                ['Put Requests', number_format($data['requests']['put_requests'])],
                ['Delete Requests', number_format($data['requests']['delete_requests'])],
                ['Total Cost', '$' . number_format($data['costs']['total_cost'], 3)],
            ];

            $this->table(['Metric', 'Value'], $basicData);

            if ($detailed) {
                $this->newLine();
                $this->info("🔍 Detailed Information");
                
                // Storage details
                $storageData = [
                    ['Total Objects', number_format($data['storage']['total_objects'])],
                    ['Total Size (Bytes)', number_format($data['storage']['total_size_bytes'])],
                    ['Total Size (Human)', $data['storage']['total_size_human']],
                ];

                $this->table(['Storage Metric', 'Value'], $storageData);

                // Request details
                $requestData = [
                    ['Get Requests', number_format($data['requests']['get_requests'])],
                    ['Put Requests', number_format($data['requests']['put_requests'])],
                    ['Delete Requests', number_format($data['requests']['delete_requests'])],
                ];

                if (isset($data['requests']['head_requests'])) {
                    $requestData[] = ['Head Requests', number_format($data['requests']['head_requests'])];
                }

                $this->table(['Request Type', 'Count'], $requestData);

                // Cost breakdown
                $costData = [
                    ['Storage Cost', '$' . number_format($data['costs']['storage_cost'], 3)],
                    ['Request Cost', '$' . number_format($data['costs']['request_cost'], 3)],
                ];

                if (isset($data['costs']['bandwidth_cost'])) {
                    $costData[] = ['Bandwidth Cost', '$' . number_format($data['costs']['bandwidth_cost'], 3)];
                }

                if (isset($data['costs']['transformation_cost'])) {
                    $costData[] = ['Transformation Cost', '$' . number_format($data['costs']['transformation_cost'], 3)];
                }

                $costData[] = ['Total Cost', '$' . number_format($data['costs']['total_cost'], 3)];

                $this->table(['Cost Type', 'Amount'], $costData);
            }

            $this->newLine();
        }

        return 0;
    }

    /**
     * Display usage data in JSON format
     * 
     * @param array $usageData
     * @return int
     */
    private function displayJsonFormat(array $usageData): int
    {
        $this->line(json_encode($usageData, JSON_PRETTY_PRINT));
        return 0;
    }

    /**
     * Display usage data in CSV format
     * 
     * @param array $usageData
     * @return int
     */
    private function displayCsvFormat(array $usageData): int
    {
        $this->line('Provider,Total Objects,Total Size,Get Requests,Put Requests,Delete Requests,Total Cost');
        
        foreach ($usageData as $provider => $data) {
            if ($data['status'] === 'error') {
                $this->line("{$provider},ERROR,ERROR,ERROR,ERROR,ERROR,ERROR");
                continue;
            }

            $line = [
                $provider,
                $data['storage']['total_objects'],
                $data['storage']['total_size_human'],
                $data['requests']['get_requests'],
                $data['requests']['put_requests'],
                $data['requests']['delete_requests'],
                '$' . number_format($data['costs']['total_cost'], 3),
            ];

            $this->line(implode(',', $line));
        }

        return 0;
    }
}

<?php

declare(strict_types=1);

namespace Subhashladumor\LaravelMulticloud\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Subhashladumor\LaravelMulticloud\Facades\LaravelMulticloud;
use Illuminate\Support\Facades\Validator;

/**
 * Cloud Controller
 * 
 * HTTP API controller for cloud operations
 * 
 * @package Subhashladumor\LaravelMulticloud\Http\Controllers
 * @author Subhash Ladumor <subhashladumor@gmail.com>
 * @license MIT
 */
class CloudController extends Controller
{
    /**
     * Upload a file to cloud storage
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // 10MB max
            'path' => 'required|string|max:255',
            'provider' => 'sometimes|string|in:aws,azure,gcp,cloudinary,alibaba,ibm,digitalocean,oracle,cloudflare',
            'options' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $file = $request->file('file');
            $path = $request->input('path');
            $provider = $request->input('provider');
            $options = $request->input('options', []);

            $cloudManager = $provider ? LaravelMulticloud::driver($provider) : LaravelMulticloud::driver();
            
            $result = $cloudManager->upload($path, $file->getContent(), $options);

            return response()->json($result, $result['status'] === 'success' ? 200 : 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download a file from cloud storage
     * 
     * @param Request $request
     * @return Response|JsonResponse
     */
    public function download(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string|max:255',
            'provider' => 'sometimes|string|in:aws,azure,gcp,cloudinary,alibaba,ibm,digitalocean,oracle,cloudflare',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $path = $request->input('path');
            $provider = $request->input('provider');

            $cloudManager = $provider ? LaravelMulticloud::driver($provider) : LaravelMulticloud::driver();
            
            $result = $cloudManager->download($path);

            if ($result['status'] === 'error') {
                return response()->json($result, 400);
            }

            // Return file as download
            return response($result['content'])
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . basename($path) . '"');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a file from cloud storage
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string|max:255',
            'provider' => 'sometimes|string|in:aws,azure,gcp,cloudinary,alibaba,ibm,digitalocean,oracle,cloudflare',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $path = $request->input('path');
            $provider = $request->input('provider');

            $cloudManager = $provider ? LaravelMulticloud::driver($provider) : LaravelMulticloud::driver();
            
            $result = $cloudManager->delete($path);

            return response()->json($result, $result['status'] === 'success' ? 200 : 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * List files in cloud storage
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'path' => 'sometimes|string|max:255',
            'provider' => 'sometimes|string|in:aws,azure,gcp,cloudinary,alibaba,ibm,digitalocean,oracle,cloudflare',
            'options' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $path = $request->input('path', '');
            $provider = $request->input('provider');
            $options = $request->input('options', []);

            $cloudManager = $provider ? LaravelMulticloud::driver($provider) : LaravelMulticloud::driver();
            
            $result = $cloudManager->list($path, $options);

            return response()->json($result, $result['status'] === 'success' ? 200 : 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check if file exists in cloud storage
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function exists(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string|max:255',
            'provider' => 'sometimes|string|in:aws,azure,gcp,cloudinary,alibaba,ibm,digitalocean,oracle,cloudflare',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $path = $request->input('path');
            $provider = $request->input('provider');

            $cloudManager = $provider ? LaravelMulticloud::driver($provider) : LaravelMulticloud::driver();
            
            $exists = $cloudManager->exists($path);

            return response()->json([
                'status' => 'success',
                'exists' => $exists,
                'path' => $path,
                'provider' => $provider ?: config('multicloud.default'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get file metadata from cloud storage
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function metadata(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string|max:255',
            'provider' => 'sometimes|string|in:aws,azure,gcp,cloudinary,alibaba,ibm,digitalocean,oracle,cloudflare',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $path = $request->input('path');
            $provider = $request->input('provider');

            $cloudManager = $provider ? LaravelMulticloud::driver($provider) : LaravelMulticloud::driver();
            
            $result = $cloudManager->getMetadata($path);

            return response()->json($result, $result['status'] === 'success' ? 200 : 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate signed URL for cloud storage object
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function signedUrl(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|string|max:255',
            'provider' => 'sometimes|string|in:aws,azure,gcp,cloudinary,alibaba,ibm,digitalocean,oracle,cloudflare',
            'expiration' => 'sometimes|integer|min:60|max:604800', // 1 minute to 7 days
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $path = $request->input('path');
            $provider = $request->input('provider');
            $expiration = $request->input('expiration', 3600);

            $cloudManager = $provider ? LaravelMulticloud::driver($provider) : LaravelMulticloud::driver();
            
            $signedUrl = $cloudManager->generateSignedUrl($path, $expiration);

            return response()->json([
                'status' => 'success',
                'signed_url' => $signedUrl,
                'path' => $path,
                'expiration' => $expiration,
                'expires_at' => now()->addSeconds($expiration)->toISOString(),
                'provider' => $provider ?: config('multicloud.default'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get usage statistics for cloud provider
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function usage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'sometimes|string|in:aws,azure,gcp,cloudinary,alibaba,ibm,digitalocean,oracle,cloudflare',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $provider = $request->input('provider');

            $cloudManager = $provider ? LaravelMulticloud::driver($provider) : LaravelMulticloud::driver();
            
            $result = $cloudManager->getUsage();

            return response()->json($result, $result['status'] === 'success' ? 200 : 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test connection to cloud provider
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function testConnection(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'provider' => 'sometimes|string|in:aws,azure,gcp,cloudinary,alibaba,ibm,digitalocean,oracle,cloudflare',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $provider = $request->input('provider');

            $cloudManager = $provider ? LaravelMulticloud::driver($provider) : LaravelMulticloud::driver();
            
            $result = $cloudManager->testConnection();

            return response()->json($result, $result['status'] === 'success' ? 200 : 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get available cloud providers
     * 
     * @return JsonResponse
     */
    public function providers(): JsonResponse
    {
        try {
            $providers = LaravelMulticloud::getAvailableDrivers();
            $enabledProviders = config('multicloud.enabled_providers', []);

            $providerList = [];
            foreach ($providers as $key => $name) {
                $providerList[] = [
                    'key' => $key,
                    'name' => $name,
                    'enabled' => $enabledProviders[$key] ?? true,
                ];
            }

            return response()->json([
                'status' => 'success',
                'providers' => $providerList,
                'default' => config('multicloud.default'),
                'count' => count($providerList),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HealthController extends Controller
{
    public function check()
    {
        $health = [
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'checks' => []
        ];

        // Database check
        try {
            DB::connection()->getPdo();
            $health['checks']['database'] = ['status' => 'healthy'];
        } catch (\Exception $e) {
            $health['status'] = 'unhealthy';
            $health['checks']['database'] = [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }

        // Storage check
        try {
            Storage::disk('public')->exists('books');
            $health['checks']['storage'] = ['status' => 'healthy'];
        } catch (\Exception $e) {
            $health['status'] = 'unhealthy';
            $health['checks']['storage'] = [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }

        // Memory check
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        $health['checks']['memory'] = [
            'status' => 'healthy',
            'usage_mb' => round($memoryUsage / 1024 / 1024, 2),
            'limit' => $memoryLimit
        ];

        return response()->json($health, $health['status'] === 'healthy' ? 200 : 503);
    }
}

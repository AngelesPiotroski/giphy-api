<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceLog;
use App\Services\ErrorLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ServiceLogController extends Controller
{
    protected ErrorLogger $logger;

    public function __construct(ErrorLogger $logger)
    {
        $this->logger = $logger;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $logs = ServiceLog::with('user')
                ->latest()
                ->paginate(20);

            return response()->json($logs);
        } catch (Throwable $e) {
            $this->logger->log('ServiceLogController@index', $e, [
                'query_params' => $request->query(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los registros de servicio.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
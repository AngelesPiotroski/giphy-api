<?php
namespace App\Services\Logging;

use App\Models\ServiceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceInteractionLogger
{
    public function log(string $service, Request $request, mixed $response, int $statusCode): void
    {
        ServiceLog::create([
            'user_id'       => Auth::id(),
            'service'       => $service,
            'request_body'  => json_encode($request->all()),
            'http_code'     => $statusCode,
            'response_body' => is_array($response) ? json_encode($response) : $response,
            'ip_address'    => $request->ip(),
        ]);
    }
}
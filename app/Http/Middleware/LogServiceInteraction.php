<?php
namespace App\Http\Middleware;

use App\Models\ServiceLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogServiceInteraction
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo registrar si es una petición a la API
        if ($request->is('api/*')) {
            $this->logInteraction($request, $response);
        }

        return $response;
    }

    protected function logInteraction(Request $request, Response $response): void
    {
        try {
            ServiceLog::create([
                'user_id'       => Auth::id(),
                'service'       => $request->route()->getActionName(),
                'request_body'  => json_encode($request->all()),
                'http_code'     => $response->getStatusCode(),
                'response_body' => $response->getContent(),
                'ip_address'    => $request->ip(),
            ]);
        } catch (\Throwable $e) {
            logger()->error('Error logging service interaction', ['exception' => $e]);
        }
    }
}
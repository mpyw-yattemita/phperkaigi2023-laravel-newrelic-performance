<?php

namespace App\Http\Middleware;

use Illuminate\Database\DatabaseManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CountQueries
{
    public function __construct(
        private readonly DatabaseManager $db,
    ) {
    }

    public function handle(Request $request, callable $next): Response|JsonResponse
    {
        if (!$request->expectsJson()) {
            return $next($request);
        }

        $this->db->enableQueryLog();
        $response = $next($request);

        assert($response instanceof Response || $response instanceof JsonResponse);
        $response->header('X-Query-Count', (string)count($this->db->getQueryLog()));

        return $response;
    }
}

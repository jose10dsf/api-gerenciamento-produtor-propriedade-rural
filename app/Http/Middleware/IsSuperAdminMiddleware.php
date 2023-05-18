<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Helpers\DefaultResponseHelper;

class IsSuperAdminMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {

            $user = auth()->user();
            if($user->is_super_admin == false){
                $response = DefaultResponseHelper::error('Permissão negada, você deve ser um super administrador para acessar o recurso', 403);
                return response()->json($response, 403);
            }
        } catch (Exception $e) {
            $response = DefaultResponseHelper::error($e->getMessage(), 500);
            return response()->json($response, 500);
        }
        return $next($request);
    }
}
<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use App\Helpers\DefaultResponseHelper;

class JwtMiddleware extends BaseMiddleware
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
            $user = JWTAuth::parseToken()->authenticate();
            if($user->activated == false){
                $response = DefaultResponseHelper::error('Sua conta está desativada', 403);
                return response()->json($response, 403);
            }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){ //Token is Invalid
            	$response = DefaultResponseHelper::error('Token é inválido', 401);
                return response()->json($response, 401);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){ //Token is Expired
                $response = DefaultResponseHelper::error('O token está expirado', 401);
                return response()->json($response, 401);
            }else{ //Authorization Token not found
                $response= DefaultResponseHelper::error('Token de autorização não encontrado', 401);
                return response()->json($response, 401);
            }
        }
        return $next($request);
    }
}
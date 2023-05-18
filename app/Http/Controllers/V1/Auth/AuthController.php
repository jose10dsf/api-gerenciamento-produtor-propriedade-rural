<?php
declare(strict_types=1);

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\JWTAuth;
use App\Helpers\DefaultResponseHelper;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    protected $jwt;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
        //$this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
         $validator = Validator::make($request->only('name', 'password'), [
            'name' => 'required',
            'password' => 'required'
        ]);
        if($validator->fails()) {
            $response = DefaultResponseHelper::error('Dados inválidos', 400, $validator->messages());
            return response()->json($response, 400);
        }
        try {
            $credentials = $request->only('name', 'password');
            if (! $token = $this->jwt->attempt($credentials)) {
                $response = DefaultResponseHelper::error('Nome e/ou senha incorretos', 401);
                return response()->json($response, 401);
            }
            if(auth()->user()->activated == false){
                $response = DefaultResponseHelper::error('Sua conta está desativada', 401);
                return response()->json($response, 401);

            }
            $result = $this->respondWithToken($token);
            $response = DefaultResponseHelper::success($result);
        } catch (Exception $e) {
            $response = DefaultResponseHelper::error($e->getMessage(), 500);
        }
        return response()->json($response, $response['status_code']);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            $result = [auth()->user()];
            $response = DefaultResponseHelper::success($result);
        } catch (Exception $e) {
            $response = DefaultResponseHelper::error($e->getMessage(), 500);
        }

        return response()->json($response, $response['status_code']);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth()->logout();
            $result = [];
            $response = DefaultResponseHelper::success($result);
        } catch (Exception $e) {
            $response = DefaultResponseHelper::error($e->getMessage(), 500);
        }

        return response()->json($response, $response['status_code']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $result = $this->respondWithToken(auth()->refresh());
            $response = DefaultResponseHelper::success($result);
        } catch (Exception $e) {
            $response = DefaultResponseHelper::error($e->getMessage(), 500);
        }

        return response()->json($response, $response['status_code']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

}
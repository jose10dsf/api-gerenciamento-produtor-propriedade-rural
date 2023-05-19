<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\AbstractController;
use App\Services\User\UserService;
use App\Helpers\DefaultResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

/**
 * Class UserController
 * @package App\Http\Controllers\V1\User
 */
class UserController extends AbstractController
{
    /**
     * @var array|string[]
     */
    protected array $searchFields = [
        'name',
    ];

    /**
     * @var array|string[]
     */
    protected array $orderByFields = [
        'id', 'name', 'created_at', 'updated_at'
    ];

    /**
     * UserController constructor.
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        parent::__construct($service);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function nameAlreadyExists(Request $request, int $id = -1): JsonResponse
    {
        try {
            $result["nameAlreadyExists"] = $this->service->nameAlreadyExists($request->all(), $id);
            $response = DefaultResponseHelper::success($result);
        } catch (Exception $e) {
            $response = DefaultResponseHelper::error($e);
        }

        return response()->json($response, $response['status_code']);
    }

}
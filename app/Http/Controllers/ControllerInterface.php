<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Interface ControllerInterface
 * @package App\Http\Controllers
 */
interface ControllerInterface
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function findAll(Request $request): JsonResponse;

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function findOneById(Request $request, int $id): JsonResponse;

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function editById(Request $request, int $id): JsonResponse;

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function delete(Request $request, int $id): JsonResponse;
}
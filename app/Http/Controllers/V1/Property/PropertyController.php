<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Property;

use App\Http\Controllers\AbstractController;
use App\Services\Property\PropertyService;
use Illuminate\Http\Request;
use App\Helpers\DefaultResponseHelper;
use App\Helpers\ModelHelper;
use Illuminate\Http\JsonResponse;

/**
 * Class PropertyController
 * @package App\Http\Controllers\V1\Property
 */
class PropertyController extends AbstractController
{
    /**
     * @var array|string[]
     */
    protected array $searchFields = [
        'name',
        'rural_cadastre',
    ];

    /**
     * PropertyController constructor.
     * @param PropertyService $service
     */
    public function __construct(PropertyService $service)
    {
        parent::__construct($service);
    }

    public function ruralCadastreAlreadyExists(Request $request, int $id = -1): JsonResponse
    {
        try {
            $result['ruralCadastreAlreadyExists'] = $this->service->ruralCadastreAlreadyExists($request->all(), $id);
            $response = DefaultResponseHelper::success($result);
        } catch (Exception $e) {
            $response = DefaultResponseHelper::error($e);
        }

        return response()->json($response, $response['status_code']);
    }
}


}
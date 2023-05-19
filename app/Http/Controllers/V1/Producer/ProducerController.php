<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Producer;

use App\Http\Controllers\AbstractController;
use App\Services\ServiceInterface;
use App\Services\Producer\ProducerService;
use App\Services\Property\PropertyService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\DefaultResponseHelper;
use App\Helpers\ModelHelper;
use App\Helpers\ProducerHelper;
use Illuminate\Http\JsonResponse;

/**
 * Class ProducerController
 * @package App\Http\Controllers\V1\Producer
 */
class ProducerController extends AbstractController
{
    /**
     * @var ServiceInterface
     */
    protected ServiceInterface $propertyService;

    /**
     * @var array|string[]
     */
    protected array $searchFields = [
        'producers.name',
        'properties.name',
        'properties.rural_cadastre',
        'producers.cpf'
    ];

    /**
     * @var array|string[]
     */
    protected array $orderByFields = [
        'producer_id',
        'property_id',
        'producer_name',
        'property_name',
        'rural_cadastre',
        'cpf'
    ];

    /**
     * ProducerController constructor.
     * @param ProducerService $service
     */
    public function __construct(ProducerService $service, PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
        parent::__construct($service);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $data = $request->all();
            $producerData = array(
                "name" => $data["producer_name"],
                "cpf" => $data["cpf"]
            );
            $propertyData = array(
                "name" => $data["property_name"],
                "rural_cadastre" => $data["rural_cadastre"]
            );
            $producer = $this->service->create($producerData);
            $propertyData["producer_id"] = $producer["id"];
            $property = $this->propertyService->create($propertyData);
            $result = ProducerHelper::formatData($producer, $property);
            $response = DefaultResponseHelper::success($result, Response::HTTP_CREATED);
        } catch (Exception $e) {
            $response = DefaultResponseHelper::error($e->getMessage());
        }

        return response()->json($response, $response['status_code']);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function editById(Request $request, int $id): JsonResponse
    {
        try {
            $data = $request->all();
            $producerData = array();
            $propertyData = array();
            if(array_key_exists('producer_name', $data)) $producerData["name"] = $data["producer_name"];
            if(array_key_exists('cpf', $data)) $producerData["cpf"] = $data["cpf"];
            if(array_key_exists('property_name', $data)) $propertyData["name"] = $data["property_name"];
            if(array_key_exists('rural_cadastre', $data)) $propertyData["rural_cadastre"] = $data["rural_cadastre"];
            $producer = $this->service->findOneById($id);
            $property = $producer['property'];
            if(array_key_exists('id', $property)) $this->propertyService->editById($property['id'], $propertyData);
            $result['registro_alterado'] = $this->service->editById($id, $producerData);
            $response = DefaultResponseHelper::success($result);
        } catch (Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $model = ModelHelper::defineModel($e->getModel());
                if(!is_null($model)){
                    $response = DefaultResponseHelper::error("$model->namePtBr não encontrado(a) para edição dos dados", 404);
                }else {
                    $response = DefaultResponseHelper::error("Não encontrado(a) para edição dos dados", 404);
                }
            } else{
                $response = DefaultResponseHelper::error($e->getMessage());
            }
        }
        return response()->json($response, $response['status_code']);
    }

    public function cpfAlreadyExists(Request $request, int $id = -1): JsonResponse
    {
        try {
            $result['cpfAlreadyExists'] = $this->service->cpfAlreadyExists($request->all(), $id);
            $response = DefaultResponseHelper::success($result);
        } catch (Exception $e) {
            $response = DefaultResponseHelper::error($e);
        }

        return response()->json($response, $response['status_code']);
    }

}
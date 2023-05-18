<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\OrderByHelper;
use App\Services\ServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Helpers\DefaultResponseHelper;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
abstract class AbstractController extends BaseController implements ControllerInterface
{
    /**
     * @var ServiceInterface
     */
    protected ServiceInterface $service;

    /**
     * @var array
     */
    protected array $searchFields = [];

    /**
     * @var array
     */
    protected array $orderByFields = [];

    /**
     * AbstractController constructor.
     * @param ServiceInterface $service
     */
    public function __construct(ServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            
            $result = $this->service->create($request->all());
            $response = DefaultResponseHelper::success($result, Response::HTTP_CREATED);
        } catch (Exception $e) {
            $response = DefaultResponseHelper::error($e->getMessage());
        }

        return response()->json($response, $response['status_code']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function findAll(Request $request): JsonResponse
    {
        try {
            $limit = (int) $request->get('limit', 10);
            $orderBy = $request->get('order_by', []);

            if (!empty($orderBy)) {
                $orderBy = OrderByHelper::treatOrderBy($orderBy, $this->orderByFields);
            }

            $searchString = $request->get('q', '');

            if (!empty($searchString)) {
                $result = $this->service->searchBy(
                    $searchString,
                    $this->searchFields,
                    $limit,
                    $orderBy
                );
            } else {
                $result = $this->service->findAll($limit, $orderBy);
            }

            $response = DefaultResponseHelper::success($result, Response::HTTP_PARTIAL_CONTENT);
        } catch (Exception $e) {
            $response = DefaultResponseHelper::error($e->getMessage());
        }
        return response()->json($response, $response['status_code']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function findByReference(Request $request): JsonResponse
    {
        try {
            $parameterValues = array_values($request->route()[2]);
            $parameterKeys = array_keys($request->route()[2]);
            $parameter = [
                'parameterValue' => $parameterValues[0],
                'parameterKey' => $parameterKeys[0]
            ];
            $limit = (int) $request->get('limit', 10);
            $orderBy = $request->get('order_by', []);

            if (!empty($orderBy)) {
                $orderBy = OrderByHelper::treatOrderBy($orderBy, $this->orderByFields);
            }

            $searchString = $request->get('q', '');

            if (!empty($searchString)) {
                $result = $this->service->findByReferenceWithSearchBy(
                    $searchString,
                    $this->searchFields,
                    $limit,
                    $orderBy,
                    $parameter
                );
            } else {
                $result = $this->service->findByReference($limit, $orderBy, $parameter);
            }

            $response = DefaultResponseHelper::success($result, Response::HTTP_PARTIAL_CONTENT);
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
    public function findOneById(Request $request, int $id): JsonResponse
    {
        try {
            $result = $this->service->findOneById($id);
            $response = DefaultResponseHelper::success($result);
        } catch (Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $model = $this->defineModel($e->getModel());
                if(!is_null($model)){
                    $response = DefaultResponseHelper::error("$model->namePtBr não encontrado(a)", 404);
                }else {
                    $response = DefaultResponseHelper::error("Não encontrado(a)", 404);
                }
            } else{
                $response = DefaultResponseHelper::error($e->getMessage());
            }
            
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
            $result['registro_alterado'] = $this->service->editById($id, $request->all());
            $response = DefaultResponseHelper::success($result);
        } catch (Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $model = $this->defineModel($e->getModel());
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

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function delete(Request $request, int $id): JsonResponse
    {
        try {
            $result['registro_deletado'] = $this->service->delete($id);
            $response = DefaultResponseHelper::success($result);
        } catch (Exception $e) {
            $response = DefaultResponseHelper::error($e->getMessage());
        }

        return response()->json($response, $response['status_code']);
    }

}
<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AbstractRepository
 * @package App\Repositories
 */
abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * AbstractRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->model::create($data)->toArray();
    }

    /**
     * @param int $limit
     * @param array $orderBy
     * @return array
     */
    public function findAll(int $limit = 10, array $orderBy = []): array
    {
        $results = $this->model::query();

        foreach ($orderBy as $key => $value) {
            if (strstr($key, '-')) {
                $key = substr($key, 1);
            }

            $results->orderBy($key, $value);
        }

        return $results->paginate($limit)
            ->appends([
                'order_by' => implode(',', array_keys($orderBy)),
                'limit' => $limit
            ])
            ->toArray();
    }

    /**
     * @param int $id
     * @return array
     */
    public function findOneById(int $id): array
    {
        return $this->model::findOrFail($id)
            ->toArray();
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function editById(int $id, array $data): bool
    {
        $result = $this->model::findOrFail($id)
            ->update($data);

        return $result ? true : false;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->model::destroy($id) ? true : false;
    }

    /**
     * @param string $string
     * @param array $searchFields
     * @param int $limit
     * @param array $orderBy
     * @return array
     */
    public function searchBy(
        string $string,
        array $searchFields,
        int $limit = 10,
        array $orderBy = []
    ): array {
        $results = $this->model::where($searchFields[0], 'like', '%' . $string . '%');

        if (count($searchFields) > 1) {
            foreach ($searchFields as $field) {
                $results->orWhere($field, 'like', '%' . $string . '%');
            }
        }

        foreach ($orderBy as $key => $value) {
            if (strstr($key, '-')) {
                $key = substr($key, 1);
            }

            $results->orderBy($key, $value);
        }

        return $results->paginate($limit)
            ->appends([
                'order_by' => implode(',', array_keys($orderBy)),
                'q' => $string,
                'limit' => $limit
            ])
            ->toArray();
    }

    /**
     * @param int $limit
     * @param array $orderBy
     * @param array $parameter
     * @return array
     */
    public function findByReference(int $limit = 10, array $orderBy = [], array $parameter = []): array
    {
        $results = $this->model::where($parameter['parameterKey'], '=', $parameter['parameterValue']);
        foreach ($orderBy as $key => $value) {
            if (strstr($key, '-')) {
                $key = substr($key, 1);
            }

            $results->orderBy($key, $value);
        }

        return $results->paginate($limit)
            ->appends([
                'order_by' => implode(',', array_keys($orderBy)),
                'limit' => $limit
            ])
            ->toArray();
    }

    /**
     * @param string $string
     * @param array $searchFields
     * @param int $limit
     * @param array $orderBy
     * @param array $parameter
     * @return array
     */
    public function findByReferenceWithSearchBy(
        string $string,
        array $searchFields,
        int $limit = 10,
        array $orderBy = [],
        array $parameter = []
    ): array {
        $results = $this->model::where($parameter['parameterKey'], '=', $parameter['parameterValue']);
        $results->where(function ($query) use ($searchFields, $string) {
            $query->where($searchFields[0], 'like', '%' . $string . '%');
            if (count($searchFields) > 1) {
                foreach ($searchFields as $field) {
                    $query->orWhere($field, 'like', '%' . $string . '%');
                }
            }
            return $query;
        }); 
        foreach ($orderBy as $key => $value) {
            if (strstr($key, '-')) {
                $key = substr($key, 1);
            }

            $results->orderBy($key, $value);
        }

        return $results->paginate($limit)
            ->appends([
                'order_by' => implode(',', array_keys($orderBy)),
                'q' => $string,
                'limit' => $limit
            ])
            ->toArray();
    }
}
<?php

declare(strict_types=1);

namespace App\Repositories\Producer;

use App\Repositories\AbstractRepository;
use App\Helpers\ProducerHelper;

/**
 * Class ProducerRepository
 * @package App\Repositories\Producer
 */
class ProducerRepository extends AbstractRepository
{
    /**
     * @param int $limit
     * @param array $orderBy
     * @return array
     */
    public function findAll(int $limit = 10, array $orderBy = []): array
    {
        $results = $this->model::query();
        $results->leftJoin('properties', 'producers.id', '=', 'properties.producer_id');
        $results->select(
            'producers.id as producer_id',
            'producers.name as producer_name',
            'producers.cpf as cpf',
            'properties.id as property_id',
            'properties.name as property_name',
            'properties.rural_cadastre as rural_cadastre'
        );
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
     * @return array
     */
    public function searchBy(
        string $string,
        array $searchFields,
        int $limit = 10,
        array $orderBy = []
    ): array {
        //$results = $this->model::
        $results = $this->model::query();
        $results->leftJoin('properties', 'producers.id', '=', 'properties.producer_id');
        $results->select(
            'producers.id as producer_id',
            'producers.name as producer_name',
            'producers.cpf as cpf',
            'properties.id as property_id',
            'properties.name as property_name',
            'properties.rural_cadastre as rural_cadastre'
        );
        $results->where($searchFields[0], 'like', '%' . $string . '%');
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
     * @param int $id
     * @return array
     */
    public function findOneById(int $id): array
    {
        $producer = $this->model::findOrFail($id);
        $property = $producer->property;
        $result = ProducerHelper::formatData($producer->toArray(), $property->toArray());
        return $result;
    }

     /**
     * @param array
     * @param int
     * @return boolean
     */
    public function cpfAlreadyExists(array $data, int $id): bool
    {
    	if($id < 0){
    		$result = $this->model::where('cpf', '=', $data['cpf'])->count();
    	} else {
    		$result = $this->model::where('id', '<>', $id)->where('cpf', '=', $data['cpf'])->count();
    	}
    	return $result > 0;
    }

}
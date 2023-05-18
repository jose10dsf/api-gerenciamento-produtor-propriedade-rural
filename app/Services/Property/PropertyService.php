<?php

declare(strict_types=1);

namespace App\Services\Property;

use App\Services\AbstractService;

/**
 * Class PropertyService
 * @package App\Services\Property
 */
class PropertyService extends AbstractService
{

     /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function editBy(int $id, array $data): bool
    {
    	unset($data['producer_id']);
        return $this->repository->editBy($id, $data);
    }

    /**
     * @param array
     * @param int
     * @return bool
     */
    public function ruralCadastreAlreadyExists(array $data, int $id): bool
    {
        return $this->repository->ruralCadastreAlreadyExists($data, $id);
    }
}
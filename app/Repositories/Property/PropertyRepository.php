<?php

declare(strict_types=1);

namespace App\Repositories\Property;

use App\Repositories\AbstractRepository;

/**
 * Class PropertyRepository
 * @package App\Repositories\Property
 */
class PropertyRepository extends AbstractRepository
{
    /**
     * @param array
     * @param int
     * @return boolean
     */
    public function ruralCadastreAlreadyExists(array $data, int $id): bool
    {
    	if($id < 0){
    		$result = $this->model::where('rural_cadastre', '=', $data['rural_cadastre'])->count();
    	} else {
    		$result = $this->model::where('id', '<>', $id)->where('rural_cadastre', '=', $data['rural_cadastre'])->count();
    	}
    	return $result > 0;
    }
}
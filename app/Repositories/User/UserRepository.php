<?php

declare(strict_types=1);

namespace App\Repositories\User;

use App\Repositories\AbstractRepository;

/**
 * Class UserRepository
 * @package App\Repositories\User
 */
class UserRepository extends AbstractRepository
{

    /**
     * @param array
     * @param int
     * @return boolean
     */
    public function nameAlreadyExists(array $data, int $id): bool
    {
    	if($id < 0){
    		$result = $this->model::where('name', '=', $data['name'])->count();
    	} else {
    		$result = $this->model::where('id', '<>', $id)->where('name', '=', $data['name'])->count();
    	}
    	return $result > 0;
    }
}
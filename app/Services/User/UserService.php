<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Services\AbstractService;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserService
 * @package App\Services\User
 */
class UserService extends AbstractService
{
    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $data['password'] = Hash::make($data['password']);
        return $this->repository->create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function editById(int $id, array $data): bool
    {
        if(isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
        }
        return $this->repository->editById($id, $data);
    }

    /**
     * @param array
     * @param int
     * @return bool
     */
    public function nameAlreadyExists(array $data, int $id): bool
    {
        return $this->repository->nameAlreadyExists($data, $id);
    }
}
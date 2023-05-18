<?php

declare(strict_types=1);

namespace App\Services\Producer;

use App\Services\AbstractService;

/**
 * Class ProducerService
 * @package App\Services\Producer
 */
class ProducerService extends AbstractService
{
    /**
     * @param array
     * @param int
     * @return bool
     */
    public function cpfAlreadyExists(array $data, int $id): bool
    {
        return $this->repository->cpfAlreadyExists($data, $id);
    }
}
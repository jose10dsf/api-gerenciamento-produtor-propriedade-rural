<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\RepositoryInterface;

/**
 * Class AbstractService
 * @package App\Services
 */
abstract class AbstractService implements ServiceInterface
{
    /**
     * @var RepositoryInterface
     */
    protected RepositoryInterface $repository;

    /**
     * AbstractService constructor.
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->repository->create($data);
    }

    /**
     * @param int $limit
     * @param array $orderBy
     * @return array
     */
    public function findAll(int $limit = 10, array $orderBy = []): array
    {
        return $this->repository->findAll($limit, $orderBy);
    }

    /**
     * @param int $id
     * @return array
     */
    public function findOneById(int $id): array
    {
        return $this->repository->findOneById($id);
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function editById(int $id, array $data): bool
    {
        return $this->repository->editById($id, $data);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * @param string $string
     * @param array $searchFields
     * @param int $limit
     * @param array $orderBy
     * @return array
     */
    public function searchBy(string $string, array $searchFields, int $limit = 10, array $orderBy = []): array
    {
        return $this->repository
            ->searchBy(
                $string,
                $searchFields,
                $limit, $orderBy
            );
    }


    /**
     * @param int $limit
     * @param array $orderBy
     * @param array $parameter
     * @return array
     */
    public function findByReference(int $limit = 10, array $orderBy = [], array $parameter = []): array
    {
        return $this->repository->findByReference($limit, $orderBy, $parameter);
    }

    /**
     * @param string $string
     * @param array $searchFields
     * @param int $limit
     * @param array $orderBy
     * @param array $parameter
     * @return array
     */
    public function findByReferenceWithSearchBy(string $string, array $searchFields, int $limit = 10, array $orderBy = [], array $parameter = []): array{
        return $this->repository
            ->findByReferenceWithSearchBy(
                $string,
                $searchFields,
                $limit, $orderBy, $parameter
            );
    }
}
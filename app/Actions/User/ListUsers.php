<?php

namespace App\Actions\User;

use App\Repositories\Eloquent\UserRepository;

class ListUsers
{
    public function __construct(protected UserRepository $repository) {}

    public function execute(int $perPage = 15)
    {
        return $this->repository->allPaginated($perPage);
    }
}

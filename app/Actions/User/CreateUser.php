<?php

namespace App\Actions\User;

use App\Models\User;
use App\Repositories\Eloquent\UserRepository;

class CreateUser
{
    public function __construct(
        protected UserRepository $repository
    ) {}

    public function execute(array $data): User
    {
        return $this->repository->create($data);
    }
}

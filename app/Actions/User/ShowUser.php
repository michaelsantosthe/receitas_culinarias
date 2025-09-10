<?php

namespace App\Actions\User;

use App\Models\User;
use App\Repositories\Eloquent\UserRepository;

class ShowUser
{
    public function __construct(
        protected UserRepository $repository
    ) {}

    public function execute(int $id): ?User
    {
        return $this->repository->find($id);
    }
}

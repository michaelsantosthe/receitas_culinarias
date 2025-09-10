<?php

namespace App\Actions\User;

use App\Repositories\Eloquent\UserRepository;

class DeleteUser
{
    public function __construct(protected UserRepository $repository) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

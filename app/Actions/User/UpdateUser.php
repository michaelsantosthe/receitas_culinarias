<?php

namespace App\Actions\User;

use App\Models\User;
use App\Repositories\Eloquent\UserRepository;

class UpdateUser
{
    public function __construct(protected UserRepository $repository) {}

    public function execute(int $id, array $data): User
    {
        return $this->repository->update($id, $data);
    }
}

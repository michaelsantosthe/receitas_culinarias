<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function allPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): User
    {
        $user = $this->find($id);
        $user->update($data);

        return $user;
    }

    public function delete(int $id): bool
    {
        return (bool) User::destroy($id);
    }
}

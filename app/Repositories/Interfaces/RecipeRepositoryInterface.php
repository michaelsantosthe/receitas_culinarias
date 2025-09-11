<?php

namespace App\Repositories\Interfaces;

use App\Models\Recipe;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RecipeRepositoryInterface
{
    public function allPaginated(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Recipe;

    public function create(array $data): Recipe;

    public function update(int $id, array $data): Recipe;

    public function delete(int $id): bool;
}

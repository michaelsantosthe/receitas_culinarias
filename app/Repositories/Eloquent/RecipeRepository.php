<?php

namespace App\Repositories\Eloquent;

use App\Models\Recipe;
use App\Repositories\Interfaces\RecipeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RecipeRepository implements RecipeRepositoryInterface
{
    public function allPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Recipe::paginate($perPage);
    }

    public function find(int $id): ?Recipe
    {
        return Recipe::find($id);
    }

    public function create(array $data): Recipe
    {
        return Recipe::create($data);
    }

    public function update(int $id, array $data): Recipe
    {
        $categories = $this->find($id);
        $categories->update($data);

        return $categories;
    }

    public function delete(int $id): bool
    {
        return (bool) Recipe::destroy($id);
    }
}

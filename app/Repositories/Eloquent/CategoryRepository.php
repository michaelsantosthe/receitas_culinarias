<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function allPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Category::paginate($perPage);
    }

    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(int $id, array $data): Category
    {
        $categories = $this->find($id);
        $categories->update($data);

        return $categories;
    }

    public function delete(int $id): bool
    {
        return (bool) Category::destroy($id);
    }
}

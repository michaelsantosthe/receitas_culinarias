<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Repositories\Eloquent\CategoryRepository;

class ShowCategory
{
    public function __construct(
        protected CategoryRepository $repository
    ) {}

    public function execute(int $id): ?Category
    {
        return $this->repository->find($id);
    }
}

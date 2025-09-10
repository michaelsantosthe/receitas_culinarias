<?php

namespace App\Actions\Category;

use App\Repositories\Eloquent\CategoryRepository;

class DeleteCategory
{
    public function __construct(protected CategoryRepository $repository) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

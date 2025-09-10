<?php

namespace App\Actions\Category;

use App\Repositories\Eloquent\CategoryRepository;

class ListCategory
{
    public function __construct(protected CategoryRepository $repository) {}

    public function execute(int $perPage = 15)
    {
        return $this->repository->allPaginated($perPage);
    }
}

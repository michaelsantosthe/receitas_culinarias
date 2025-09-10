<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Repositories\Eloquent\CategoryRepository;

class CreateCategory
{
    public function __construct(
        protected CategoryRepository $repository
    ) {}

    public function execute(array $data): Category
    {
        return $this->repository->create($data);
    }
}

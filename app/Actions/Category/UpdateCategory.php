<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Repositories\Eloquent\CategoryRepository;

class UpdateCategory
{
    public function __construct(
        protected CategoryRepository $repository
    ) {}

    public function execute(int $id, array $data): Category
    {
        return $this->repository->update($id, $data);
    }
}

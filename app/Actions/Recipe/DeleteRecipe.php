<?php

namespace App\Actions\Recipe;

use App\Repositories\Eloquent\RecipeRepository;

class DeleteRecipe
{
    public function __construct(protected RecipeRepository $repository) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}

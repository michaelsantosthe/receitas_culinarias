<?php

namespace App\Actions\Recipe;

use App\Models\Recipe;
use App\Repositories\Eloquent\RecipeRepository;

class ShowRecipe
{
    public function __construct(
        protected RecipeRepository $repository
    ) {}

    public function execute(int $id): ?Recipe
    {
        return $this->repository->find($id);
    }
}

<?php

namespace App\Actions\Recipe;

use App\Models\Recipe;
use App\Repositories\Eloquent\RecipeRepository;

class CreateRecipe
{
    public function __construct(
        protected RecipeRepository $repository
    ) {}

    public function execute(array $data): Recipe
    {
        return $this->repository->create($data);
    }
}

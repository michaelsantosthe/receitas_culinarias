<?php

namespace App\Actions\Recipe;

use App\Models\Recipe;
use App\Repositories\Eloquent\RecipeRepository;

class UpdateRecipe
{
    public function __construct(
        protected RecipeRepository $repository
    ) {}

    public function execute(int $id, array $data): Recipe
    {
        return $this->repository->update($id, $data);
    }
}

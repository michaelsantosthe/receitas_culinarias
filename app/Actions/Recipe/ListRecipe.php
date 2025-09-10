<?php

namespace App\Actions\Recipe;

use App\Repositories\Eloquent\RecipeRepository;

class ListRecipe
{
    public function __construct(protected RecipeRepository $repository) {}

    public function execute(int $perPage = 15)
    {
        return $this->repository->allPaginated($perPage);
    }
}

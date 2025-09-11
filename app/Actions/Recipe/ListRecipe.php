<?php

namespace App\Actions\Recipe;

use App\Repositories\Eloquent\RecipeRepository;
use Auth;

class ListRecipe
{
    public function __construct(protected RecipeRepository $repository) {}

    public function execute(int $perPage = 15)
    {
        $userId = Auth::id();

        return $this->repository->allPaginated($userId, $perPage);
    }
}

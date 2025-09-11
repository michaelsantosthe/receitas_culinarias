<?php

namespace App\Http\Controllers\Api;

use App\Actions\Recipe\CreateRecipe;
use App\Actions\Recipe\DeleteRecipe;
use App\Actions\Recipe\ListRecipe;
use App\Actions\Recipe\UpdateRecipe;
use App\Http\Controllers\Controller;
use App\Http\Requests\Recipe\RecipeRequest;
use App\Http\Requests\Recipe\UpdateRecipeRequest;
use App\Models\Recipe;
use Throwable;

class RecipeController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/recipes",
     *   tags={"Recipes"},
     *   summary="Listagem de receitas",
     *   description="Retorna todas as receitas cadastradas de forma paginada.",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Response(
     *     response=200,
     *     description="Lista de receitas",
     *
     *     @OA\JsonContent(
     *       type="array",
     *
     *       @OA\Items(
     *
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="name", type="string", example="Bolo de Cenoura"),
     *         @OA\Property(property="user_id", type="integer", example=1),
     *         @OA\Property(property="category_id", type="integer", example=3),
     *         @OA\Property(property="preparation_time", type="integer", example=60),
     *         @OA\Property(property="portion", type="integer", example=8),
     *         @OA\Property(property="preparation_mode", type="string", example="Misture todos os ingredientes e leve ao forno."),
     *         @OA\Property(property="ingredients", type="string", example="3 ovos, 2 xícaras de farinha, 1 xícara de óleo"),
     *         @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-10T12:00:00Z"),
     *         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-10T12:10:00Z")
     *       )
     *     )
     *   ),
     *
     *   @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function index(ListRecipe $action)
    {
        $recipes = $action->execute();

        return response()->json($recipes);
    }

    /**
     * @OA\Post(
     *   path="/api/recipes",
     *   tags={"Recipes"},
     *   summary="Criar nova receita",
     *   description="Cadastra uma nova receita no sistema.",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\JsonContent(
     *       type="object",
     *       required={"name","user_id","category_id"},
     *
     *       @OA\Property(property="name", type="string", example="Bolo de Chocolate"),
     *       @OA\Property(property="user_id", type="integer", example=1),
     *       @OA\Property(property="category_id", type="integer", example=3),
     *       @OA\Property(property="preparation_time", type="integer", example=45),
     *       @OA\Property(property="portion", type="integer", example=10),
     *       @OA\Property(property="preparation_mode", type="string", example="Bata os ovos, açúcar e chocolate, asse por 40 min."),
     *       @OA\Property(property="ingredients", type="string", example="4 ovos, 2 xícaras de açúcar, 1 xícara de chocolate em pó")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=201,
     *     description="Receita cadastrada com sucesso",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Receita cadastrada com sucesso.")
     *     )
     *   ),
     *
     *   @OA\Response(response=422, description="Erro de validação"),
     *   @OA\Response(response=500, description="Erro ao criar a receita")
     * )
     */
    public function store(RecipeRequest $request, CreateRecipe $action)
    {
        try {
            $action->execute($request->validated());

            return response()->json([
                'message' => 'Receita cadastrada com sucesso.',
            ], 201);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao criar a Receita.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *   path="/api/recipes/{id}",
     *   tags={"Recipes"},
     *   summary="Exibir receita",
     *   description="Busca uma receita específica pelo ID.",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="id", in="path", required=true,
     *     description="ID da receita",
     *
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Receita encontrada",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="id", type="integer", example=1),
     *       @OA\Property(property="name", type="string", example="Lasanha à Bolonhesa"),
     *       @OA\Property(property="user_id", type="integer", example=2),
     *       @OA\Property(property="category_id", type="integer", example=4),
     *       @OA\Property(property="preparation_time", type="integer", example=90),
     *       @OA\Property(property="portion", type="integer", example=6),
     *       @OA\Property(property="preparation_mode", type="string", example="Monte camadas de massa, carne e queijo, leve ao forno."),
     *       @OA\Property(property="ingredients", type="string", example="Massa de lasanha, molho bolonhesa, queijo mussarela"),
     *       @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-09T10:00:00Z"),
     *       @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-09T12:00:00Z")
     *     )
     *   ),
     *
     *   @OA\Response(response=404, description="Receita não encontrada"),
     *   @OA\Response(response=500, description="Erro ao buscar a receita")
     * )
     */
    public function show(Recipe $recipe)
    {
        return response()->json($recipe, 200);
    }

    /**
     * @OA\Put(
     *   path="/api/recipes/{id}",
     *   tags={"Recipes"},
     *   summary="Atualizar receita",
     *   description="Atualiza os dados de uma receita existente.",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="id", in="path", required=true,
     *     description="ID da receita",
     *
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="name", type="string", example="Bolo de Fubá"),
     *       @OA\Property(property="category_id", type="integer", example=2),
     *       @OA\Property(property="preparation_time", type="integer", example=50),
     *       @OA\Property(property="portion", type="integer", example=12),
     *       @OA\Property(property="preparation_mode", type="string", example="Misture os ingredientes secos e líquidos, asse por 50 minutos."),
     *       @OA\Property(property="ingredients", type="string", example="Fubá, ovos, açúcar, leite")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Receita atualizada com sucesso",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Receita atualizada com sucesso.")
     *     )
     *   ),
     *
     *   @OA\Response(response=404, description="Receita não encontrada"),
     *   @OA\Response(response=500, description="Erro ao atualizar a receita")
     * )
     */
    public function update(int $id, UpdateRecipeRequest $request, UpdateRecipe $action)
    {
        try {
            $action->execute($id, $request->validated());

            return response()->json([
                'message' => 'Receita atualizada com sucesso.',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao atualizar Receita.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *   path="/api/recipes/{id}",
     *   tags={"Recipes"},
     *   summary="Deletar receita",
     *   description="Remove uma receita existente pelo ID.",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="id", in="path", required=true,
     *     description="ID da receita",
     *
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Receita deletada com sucesso",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Receita deletada com sucesso.")
     *     )
     *   ),
     *
     *   @OA\Response(response=404, description="Receita não encontrada"),
     *   @OA\Response(response=500, description="Erro ao deletar a receita")
     * )
     */
    public function destroy(int $id, DeleteRecipe $action)
    {
        try {
            $action->execute($id);

            return response()->json([
                'message' => 'Receita deletada com sucesso.',
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao deletar Receita.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *   path="/api/list_recipes",
     *   tags={"Public Recipes"},
     *   summary="Listagem pública de receitas",
     *   description="Retorna uma lista de receitas disponível para visitantes (sem autenticação).",
     *
     *   @OA\Response(
     *     response=200,
     *     description="Lista de receitas públicas",
     *
     *     @OA\JsonContent(
     *       type="array",
     *
     *       @OA\Items(
     *
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="name", type="string", example="Bolo de Cenoura"),
     *         @OA\Property(property="preparation_time", type="integer", example=60),
     *         @OA\Property(property="portion", type="integer", example=8),
     *         @OA\Property(property="ingredients", type="string", example="3 ovos, 2 xícaras de farinha"),
     *         @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-10T12:00:00Z")
     *       )
     *     )
     *   )
     * )
     */
    public function listRecipe(ListRecipe $action)
    {
        $recipes = $action->execute();

        return response()->json($recipes);
    }

    /**
     * @OA\Get(
     *   path="/api/recipes/{id}/print",
     *   tags={"Recipes"},
     *   summary="Imprimir receita",
     *   description="Retorna os dados formatados de uma receita específica para impressão.",
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID da receita",
     *
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Receita encontrada e pronta para impressão",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="id", type="integer", example=1),
     *       @OA\Property(property="name", type="string", example="Lasanha à Bolonhesa"),
     *       @OA\Property(property="ingredients", type="string", example="Massa de lasanha, molho bolonhesa, queijo mussarela"),
     *       @OA\Property(property="preparation_mode", type="string", example="Monte camadas de massa, carne e queijo, leve ao forno."),
     *       @OA\Property(property="preparation_time", type="integer", example=90),
     *       @OA\Property(property="portion", type="integer", example=6),
     *       @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-09T10:00:00Z")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=404,
     *     description="Receita não encontrada",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Receita não encontrada.")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=500,
     *     description="Erro ao gerar impressão da receita",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Erro ao gerar impressão da receita."),
     *       @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
     *     )
     *   )
     * )
     */
    public function printRecipe(int $id)
    {
        $recipe = Recipe::find($id);

        if (! $recipe) {
            return response()->json([
                'message' => 'Receita não encontrada.',
            ], 404);
        }

        return response()->json([
            'id' => $recipe->id,
            'name' => $recipe->name,
            'ingredients' => $recipe->ingredients,
            'preparation_mode' => $recipe->preparation_mode,
            'preparation_time' => $recipe->preparation_time,
            'portion' => $recipe->portion,
            'created_at' => $recipe->created_at,
        ], 200);
    }
}

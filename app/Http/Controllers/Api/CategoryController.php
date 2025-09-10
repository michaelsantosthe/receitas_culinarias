<?php

namespace App\Http\Controllers\Api;

use App\Actions\Category\CreateCategory;
use App\Actions\Category\DeleteCategory;
use App\Actions\Category\ListCategory;
use App\Actions\Category\ShowCategory;
use App\Actions\Category\UpdateCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\CategoryRequest;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use Throwable;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/categories",
     *   tags={"Categories"},
     *   summary="Listagem de Categorias",
     *   description="Retorna todas as categorias cadastradas.",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Response(
     *     response=200,
     *     description="Lista de categorias",
     *
     *     @OA\JsonContent(
     *       type="array",
     *
     *       @OA\Items(
     *         type="object",
     *
     *         @OA\Property(property="id", type="integer", example=1),
     *         @OA\Property(property="name", type="string", example="Bolos"),
     *         @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-10T12:00:00Z"),
     *         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-10T12:05:00Z")
     *       )
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=401,
     *     description="Não autenticado",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Unauthenticated.")
     *     )
     *   )
     * )
     */
    public function index(ListCategory $action)
    {
        $categories = $action->execute();

        return response()->json($categories);
    }

    /**
     * @OA\Post(
     *   path="/api/categories",
     *   tags={"Categories"},
     *   summary="Criar nova categoria",
     *   description="Cadastra uma nova categoria.",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\RequestBody(
     *     required=true,
     *     description="Dados necessários para criar uma categoria",
     *
     *     @OA\JsonContent(
     *       type="object",
     *       required={"name"},
     *
     *       @OA\Property(property="name", type="string", example="Sobremesas")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=201,
     *     description="Categoria cadastrada com sucesso",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Categoria cadastrada com sucesso.")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=422,
     *     description="Erro de validação",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="The given data was invalid."),
     *       @OA\Property(
     *         property="errors",
     *         type="object",
     *         example={
     *           "name": {"The name field is required."}
     *         }
     *       )
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=500,
     *     description="Erro interno ao criar categoria",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Erro ao criar a categoria."),
     *       @OA\Property(property="error", type="string", example="SQLSTATE[23000]: Integrity constraint violation...")
     *     )
     *   )
     * )
     */
    public function store(CategoryRequest $request, CreateCategory $action)
    {
        try {
            $user = $action->execute($request->validated());

            return response()->json([
                'message' => 'Categoria cadastrada com sucesso.',
            ], 201);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao criar a categoria.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *   path="/api/categories/{id}",
     *   tags={"Categories"},
     *   summary="Exibir categoria",
     *   description="Busca uma categoria específica pelo ID.",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID da categoria",
     *
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Categoria encontrada",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="id", type="integer", example=1),
     *       @OA\Property(property="name", type="string", example="Massas"),
     *       @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-10T10:00:00Z"),
     *       @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-10T11:00:00Z")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=404,
     *     description="Categoria não encontrada",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Categoria não encontrada.")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=500,
     *     description="Erro interno ao buscar categoria",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Erro ao buscar categoria."),
     *       @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
     *     )
     *   )
     * )
     */
    public function show(int $id, ShowCategory $action)
    {
        try {
            $category = $action->execute($id);

            if (! $category) {
                return response()->json([
                    'message' => 'Receita não encontrada.',
                ], 404);
            }

            return response()->json($category, 200);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao buscar Receita.',
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *   path="/api/categories/{id}",
     *   tags={"Categories"},
     *   summary="Atualizar categoria",
     *   description="Atualiza os dados de uma categoria existente.",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID da categoria",
     *
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *
     *   @OA\RequestBody(
     *     required=true,
     *     description="Dados para atualizar categoria",
     *
     *     @OA\JsonContent(
     *       type="object",
     *       required={"name"},
     *
     *       @OA\Property(property="name", type="string", example="Bolos e Tortas")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Categoria atualizada com sucesso",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Categoria atualizado com sucesso.")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=404,
     *     description="Categoria não encontrada",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Categoria não encontrada.")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=500,
     *     description="Erro interno ao atualizar categoria",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Erro ao atualizar Categoria."),
     *       @OA\Property(property="error", type="string", example="SQLSTATE[23000]: ...")
     *     )
     *   )
     * )
     */
    public function update(int $id, UpdateCategoryRequest $request, UpdateCategory $action)
    {
        try {
            $data = $request->validated();
            $action->execute($id, $data);

            return response()->json([
                'message' => 'Categoria atualizado com sucesso.',
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao atualizar Categoria.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *   path="/api/categories/{id}",
     *   tags={"Categories"},
     *   summary="Deletar categoria",
     *   description="Remove uma categoria existente pelo ID.",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID da categoria",
     *
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Categoria deletada com sucesso",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Categoria deletada com sucesso.")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=404,
     *     description="Categoria não encontrada",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Categoria não encontrada.")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=500,
     *     description="Erro interno ao deletar categoria",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Erro ao deletar Categoria."),
     *       @OA\Property(property="error", type="string", example="SQLSTATE[23000]: ...")
     *     )
     *   )
     * )
     */
    public function destroy(int $id, DeleteCategory $action)
    {
        try {
            $action->execute($id);

            return response()->json([
                'message' => 'Categoria deletada com sucesso.',
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao deletar Categoria.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

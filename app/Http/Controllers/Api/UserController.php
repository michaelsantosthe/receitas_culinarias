<?php

namespace App\Http\Controllers\Api;

use App\Actions\User\CreateUser;
use App\Actions\User\DeleteUser;
use App\Actions\User\ListUsers;
use App\Actions\User\UpdateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Throwable;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/users",
     *   summary="Listagem de Usuários",
     *   description="Retorna a lista de usuários paginada.",
     *   tags={"Users"},
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="per_page",
     *     in="query",
     *     required=false,
     *     description="Quantidade de registros por página",
     *
     *     @OA\Schema(type="integer", minimum=1, maximum=100, default=15)
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Lista paginada de usuários",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(
     *         property="data",
     *         type="array",
     *
     *         @OA\Items(
     *           type="object",
     *
     *           @OA\Property(property="id", type="integer", example=1),
     *           @OA\Property(property="name", type="string", example="Michael Santos"),
     *           @OA\Property(property="email", type="string", format="email", example="michael@example.com"),
     *           @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-09T12:00:00Z"),
     *           @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-09T12:10:00Z")
     *         )
     *       ),
     *       @OA\Property(
     *         property="links",
     *         type="object",
     *         @OA\Property(property="first", type="string", example="http://localhost:8080/api/users?page=1"),
     *         @OA\Property(property="last", type="string", example="http://localhost:8080/api/users?page=5"),
     *         @OA\Property(property="prev", type="string", nullable=true, example=null),
     *         @OA\Property(property="next", type="string", nullable=true, example="http://localhost:8080/api/users?page=2")
     *       ),
     *       @OA\Property(
     *         property="meta",
     *         type="object",
     *         @OA\Property(property="current_page", type="integer", example=1),
     *         @OA\Property(property="from", type="integer", example=1),
     *         @OA\Property(property="last_page", type="integer", example=5),
     *         @OA\Property(property="path", type="string", example="http://localhost:8080/api/users"),
     *         @OA\Property(property="per_page", type="integer", example=15),
     *         @OA\Property(property="to", type="integer", example=15),
     *         @OA\Property(property="total", type="integer", example=72)
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
    public function index(ListUsers $action)
    {
        $users = $action->execute();

        return UserResource::collection($users)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @OA\Post(
     *   path="/api/users",
     *   summary="Criar novo usuário",
     *   description="Cria um usuário e retorna o registro criado.",
     *   tags={"Users"},
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\RequestBody(
     *     required=true,
     *     description="Dados necessários para criação de um usuário",
     *
     *     @OA\JsonContent(
     *       type="object",
     *       required={"name","email","password"},
     *
     *       @OA\Property(property="name", type="string", example="Michael Santos Novo"),
     *       @OA\Property(property="email", type="string", format="email", example="michaelsantos2020.the@hotmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="12345678")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=201,
     *     description="Usuário criado com sucesso",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Usuário cadastrado com sucesso."),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="name", type="string", example="Michael Santos Novo"),
     *         @OA\Property(property="email", type="string", format="email", example="michaelsantos2020.the@hotmail.com"),
     *       )
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
     *       @OA\Property(property="message", type="string", example="Os dados fornecidos são inválidos."),
     *       @OA\Property(
     *         property="errors",
     *         type="object",
     *         example={
     *            "email": {"Este e-mail já está cadastrado."},
     *            "password": {"A senha deve ter pelo menos 6 caracteres."}
     *         }
     *       )
     *     )
     *   ),
     *
     *   @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function store(UserRequest $request, CreateUser $action)
    {
        try {
            $user = $action->execute($request->validated());

            return response()->json([
                'message' => 'Usuário cadastrado com sucesso.',
                'data' => new UserResource($user),
            ], 201);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao criar usuário.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *   path="/api/users/{id}",
     *   summary="Atualizar usuário",
     *   description="Atualiza os dados de um usuário existente e retorna uma mensagem de sucesso.",
     *   tags={"Users"},
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID do usuário a ser atualizado",
     *
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *
     *   @OA\RequestBody(
     *     required=true,
     *     description="Dados para atualizar o usuário",
     *
     *     @OA\JsonContent(
     *       type="object",
     *       required={"name","email"},
     *
     *       @OA\Property(property="name", type="string", example="Michael Santos Novo"),
     *       @OA\Property(property="email", type="string", format="email", example="michaelsantos.the@hotmail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="12345678")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Usuário atualizado com sucesso",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Usuário atualizado com sucesso.")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=404,
     *     description="Usuário não encontrado",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Usuário não encontrado")
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
     *       @OA\Property(property="message", type="string", example="Os dados fornecidos são inválidos."),
     *       @OA\Property(
     *         property="errors",
     *         type="object",
     *         example={
     *           "email": {"Este e-mail já está cadastrado."},
     *           "password": {"A senha deve ter pelo menos 6 caracteres."}
     *         }
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
    public function update(int $id, UpdateUserRequest $request, UpdateUser $action)
    {
        try {
            $data = $request->validated();
            $action->execute($id, $data);

            return response()->json([
                'message' => 'Usuário atualizado com sucesso.',
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao atualizar usuário.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Deletar um usuário específico",
     *     tags={"Users"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Deleter um usuário",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(response=204, description="Usuário deletado com sucesso"),
     *     @OA\Response(response=404, description="Usuário não encontrado")
     * )
     */
    public function destroy(int $id, DeleteUser $action)
    {
        try {
            $action->execute($id);

            return response()->json([
                'message' => 'Usuário deletado com sucesso.',
            ], 200);

        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Erro ao deletar usuário.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

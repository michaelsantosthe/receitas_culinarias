<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\User\CreateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use App\Http\Resources\UserResource;
use Throwable;

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     *   path="/api/register",
     *   tags={"Auth"},
     *   summary="Registro de novo usuário",
     *   description="Cria um novo usuário no sistema e retorna os dados cadastrados.",
     *
     *   @OA\RequestBody(
     *     required=true,
     *     description="Dados necessários para registrar um usuário",
     *
     *     @OA\JsonContent(
     *       type="object",
     *       required={"name","email","password"},
     *
     *       @OA\Property(property="name", type="string", example="Michael Santos"),
     *       @OA\Property(property="email", type="string", format="email", example="michael@example.com"),
     *       @OA\Property(property="password", type="string", format="password", example="12345678")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=201,
     *     description="Usuário cadastrado com sucesso",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Usuário cadastrado com sucesso."),
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         @OA\Property(property="id", type="integer", example=10),
     *         @OA\Property(property="name", type="string", example="Michael Santos"),
     *         @OA\Property(property="email", type="string", format="email", example="michael@example.com"),
     *         @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-10T12:00:00Z"),
     *         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-10T12:05:00Z")
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
     *       @OA\Property(property="message", type="string", example="The given data was invalid."),
     *       @OA\Property(
     *         property="errors",
     *         type="object",
     *         example={
     *           "email": {"The email has already been taken."},
     *           "password": {"The password must be at least 6 characters."}
     *         }
     *       )
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=500,
     *     description="Erro interno ao criar usuário",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Erro ao criar usuário."),
     *       @OA\Property(property="error", type="string", example="SQLSTATE[23000]: Integrity constraint violation...")
     *     )
     *   )
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
}

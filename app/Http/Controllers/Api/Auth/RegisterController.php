<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\User\CreateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Throwable;

class RegisterController extends Controller
{
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

<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *   path="/api/login",
     *   tags={"Auth"},
     *   summary="Autenticar e gerar token",
     *
     *   @OA\RequestBody(
     *     required=true,
     *
     *     @OA\JsonContent(
     *       required={"email","password"},
     *
     *       @OA\Property(property="email", type="string", format="email", example="michael@example.com"),
     *       @OA\Property(property="password", type="string", format="password", example="12345678")
     *     )
     *   ),
     *
     *   @OA\Response(
     *     response=200,
     *     description="Sucesso ao logar",
     *
     *     @OA\JsonContent(
     *
     *       @OA\Property(property="token", type="string", example="1|qwerty..."),
     *     )
     *   ),
     *
     *   @OA\Response(response=401, description="Credenciais inválidas")
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $token = Auth::user()->createToken('api_token')->plainTextToken;

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'Credenciais inválidas!'], 401);
    }

    /**
     * @OA\Post(
     *   path="/api/logout",
     *   tags={"Auth"},
     *   summary="Logout (revoga o token atual)",
     *   description="Revoga o token de acesso usado na requisição atual, invalidando o Bearer Token.",
     *   security={{"bearerAuth":{}}},
     *
     *   @OA\Response(
     *     response=200,
     *     description="Logout efetuado com sucesso",
     *
     *     @OA\JsonContent(
     *       type="object",
     *
     *       @OA\Property(property="message", type="string", example="Logout realizado com sucesso.")
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
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso.',
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 * title="API de Receitas Culinarias",
 *version="1.0.0",
 *),
 *
 *@OA\SecurityScheme(
 *securityScheme="bearerAuth",
 *in="header",
 *name="bearerAuth",
 *type="http",
 *scheme="bearer",
 *),
 */
abstract class Controller {}

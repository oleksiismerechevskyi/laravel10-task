<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Response;

class TokenController extends Controller
{
     /**
     * @OA\Get(
     *     path="token",
     *     @OA\Response(response="200", description="Returns a token for user registration")
     * )
     */
    public function index(Request $request) {
        $expriresAt = new \DateTime('+40 min');

        $user = new User();
        $token = $user->createToken(
            uniqid('token-'),
            ['*'],
            $expriresAt
        );

        return Response::json(
            [
                'success' => 'true',
                'token' => $token->plainTextToken
            ]
            );
    }
}

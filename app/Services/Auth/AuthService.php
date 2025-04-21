<?php

namespace App\Services\Auth;

use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepository $_authRepository)
    {
        $this->authRepository = $_authRepository;
    }

    public function login($request)
    {
        $validator  = Validator::make($request->all(), [
            'email'                         => 'required|email',
            'password'                      => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return [
                "code"      => Response::HTTP_BAD_REQUEST,
                "request"   => $validator->errors(),
                "process"   => "validation"
            ];
        } else {
            $userLogin = $this->authRepository->getUserByEmailPassword($request->email, $request->password);

            if (!$userLogin) {
                return [
                    "code"      => Response::HTTP_UNAUTHORIZED,
                    "message"   => 'User Invalid!',
                    "process"   => "login"
                ];
            }

            if (!$token = Auth()->attempt($validator->validated())) {
                return [
                    "code"      => Response::HTTP_UNAUTHORIZED,
                    "message"   => 'password incorrect!',
                    "process"   => "login"
                ];
            }
            return [
                'code'      => Response::HTTP_OK,
                'response'  => $this->createNewToken($token)
            ];
        }
    }

    public function createNewToken($token)
    {
        return [
            "token"         => $token,
            "token_type"    => "bearer",
            "expired"       => Auth::factory()->getTTL() . " minutes",
            "user"          => $this->authRepository->getUser()
        ];
    }
}

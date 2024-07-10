<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmingOtpRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\SendOtpRequest;
use App\Http\Requests\StoreNewPasswordRequest;
use App\Http\Traits\Helpers;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use Helpers;

    public function loginUser(LoginRequest $loginRequest): JsonResponse
    {
        return $loginRequest->signIn();
    }

    /**
     * Create a new user account
     *
     * @param \App\Http\Requests\RegisterUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createNewUser(RegisterUserRequest $request): JsonResponse
    {
        return $request->saveUser();
    }

    public function logoutUser(User $user): JsonResponse
    {
        try {
            $user->tokens()->delete();

            return $this->successResponse('User account logout!');
        } catch (Exception $e) {
            return $this->errorResponse('Logging out user failed!');
        }
    }

    public function sendOtp(SendOtpRequest $request): JsonResponse
    {
        return $request->dispatchOtp();
    }

    public function confirmingOtp(ConfirmingOtpRequest $request): JsonResponse
    {
        return $request->confirm();
    }

    public function saveNewPassword(User $user, StoreNewPasswordRequest $request): JsonResponse
    {
        return $request->savePassword($user);
    }
}

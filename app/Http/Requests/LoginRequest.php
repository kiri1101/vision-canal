<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Http\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    use Helpers;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pseudo' => 'required|string',
            'password' => 'required|string|min:6'
        ];
    }

    public function signIn(): JsonResponse
    {
        $phone = $this->removeSpaceBetweenStringChar(trim($this->input('pseudo')));

        if (Auth::attempt(['phone' => $phone, 'password' => $this->input('password')])) {
            $user = User::userWithPhone($phone)->first();

            $token = $user->createToken($this->userAgent())->plainTextToken;

            return $this->successResponse('user logged in!', [
                'token' => $token,
                'user' => new UserResource($user)
            ]);
        } else {
            info('login failed: ', [
                'pseudo' => $this->input('pseudo'),
                'password' => $this->input('password')
            ]);
            return $this->errorResponse('invalid credentials', [], 5001);
        }
    }
}

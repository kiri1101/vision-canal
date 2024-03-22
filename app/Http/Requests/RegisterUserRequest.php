<?php

namespace App\Http\Requests;

use App\Http\Resources\UserResource;
use App\Http\Traits\Helpers;
use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Models\AuthorizationToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string',
            'mail' => str_word_count($this->input('mail')) > 0 ? 'required|email' : '',
            'promo' => str_word_count($this->input('promo')) > 0 ? 'required|string|max:255' : '',
            'terms' => 'required|Boolean'
        ];
    }

    public function saveUser(): JsonResponse
    {
        DB::beginTransaction();

        if ($this->input('terms')) {
            try {
                $user = User::updateOrCreate(
                    [
                        'phone' => $this->removeSpaceBetweenStringChar(trim($this->input('phone'))),
                    ],
                    [
                        'token_id' => $this->deviceToken->id,
                        'uuid' => Str::uuid(),
                        'name' => trim($this->input('name')),
                        'phone' => $this->removeSpaceBetweenStringChar(trim($this->input('phone')))
                    ]
                );

                $user->profile()->create([
                    'address' => trim($this->input('address')),
                    'state' => trim($this->input('town')),
                    'agent' => $this->userAgent(),
                    'ip_address' => $this->ip(),
                    'promo_code' => $this->has('promo') ? trim($this->input('promo')) : null
                ]);

                $token = $user->createToken($this->userAgent())->plainTextToken;

                DB::commit();

                return $this->successResponse(Lang::get('messages.success.user_created', [], 'en'), [
                    'token' => $token,
                    'user' => new UserResource($user)
                ]);
            } catch (Exception $e) {
                DB::rollBack();

                Log::critical($e->getMessage(), [
                    'trace' => $e
                ]);

                return $this->errorResponse('operation failed');
            }
        } else {
            return $this->errorResponse('Please read and confirm our terms and conditions to proceed');
        }
    }
}

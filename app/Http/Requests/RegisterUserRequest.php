<?php

namespace App\Http\Requests;

use Exception;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use App\Models\AuthorizationToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    use Helpers;

    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

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
            'country' => 'required|string|exists:countries,slug',
            'name' => 'required|string|max:255',
            'town' => 'required|string|max:255',
            'password' => 'required|string|confirmed|min:4',
            'password_confirmation' => 'required|string|min:4',
            'phone' => 'required|string|unique:users,phone',
            'mail' => str_word_count($this->input('mail')) > 0 ? 'required|email' : '',
            'promo' => str_word_count($this->input('promo')) > 0 ? 'required|string|max:255' : '',
            'terms' => 'required|Boolean'
        ];
    }

    public function saveUser(): JsonResponse
    {
        DB::beginTransaction();

        $phone = $this->removeSpaceBetweenStringChar(trim($this->input('phone')));

        if ($this->input('terms')) {
            try {
                $user = User::userWithPhone($phone)->get();

                if ($user->count() > 0) {
                    return $this->errorResponse(Lang::get('messages.error.server_error.user_account_exists'));
                } else {
                    $user = DB::transaction(function () use ($phone) {
                        return tap(User::create([
                            'uuid' => Str::uuid(),
                            'token_id' => $this->deviceToken->id,
                            'name' => trim($this->input('name')),
                            'phone' => $phone,
                            'email' => $this->has('mail') ? $this->input('mail') : null,
                            'password' => Hash::make($this->input('password')),
                            'is_admin' => false
                        ]), function (User $user) {
                            $this->createTeam($user);
                            $user->profile()->create([
                                'state' => trim($this->input('town')),
                                'country' => $this->input('country'),
                                'agent' => $this->userAgent(),
                                'ip_address' => $this->ip(),
                                'promo_code' => $this->has('promo') ? trim($this->input('promo')) : null
                            ]);
                            $user->account()->create([]);
                        });
                    });

                    $token = $user->createToken($this->userAgent())->plainTextToken;

                    DB::commit();

                    return $this->successResponse(Lang::get('messages.success.user_created', [], 'en'), [
                        'token' => $token,
                        'user' => new UserResource($user)
                    ]);
                }
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

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0] . "'s Team",
            'personal_team' => true,
        ]));
    }
}

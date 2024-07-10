<?php

namespace App\Http\Requests;

use App\Models\Team;
use App\Models\User;
use App\Rules\Role;
use Illuminate\Support\Str;
use App\Http\Traits\Helpers;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Http\FormRequest;

class AdminCreateUserRequest extends FormRequest
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
            'role' => ['required', new Role],
            'full_name' => 'required|string',
            'mail' => $this->has('mail') ? 'required|email' : '',
            'phone_number' => 'required|string|unique:users,phone',
            'home_address' => $this->has('home_address') ? 'required|string' : '',
            'city' => $this->has('city') ? 'required|string' : '',
            'country_of_origin' => $this->has('country_of_origin') ? 'required|string' : '',
            'profession' => $this->has('profession') ? 'required|string' : '',
            'password' => $this->has('password') ? 'required|string|confirmed|min:4' : '',
            'password_confirmation' => $this->has('password_confirmation') ? 'required|string|max:255' : '',
        ];
    }

    public function createNewUser(): RedirectResponse
    {
        $phone =  $this->removeSpaceBetweenStringChar(trim($this->input('phone_number')));

        $user = User::userWithPhone($phone)->first();

        if (collect($user)->isNotEmpty()) {
            return back()->withErrors(['error' => 'Account with phone number exists']);
        }

        try {
            DB::transaction(function () use ($phone) {
                return tap(User::create([
                    'uuid' => Str::uuid(),
                    'name' => $this->input('full_name'),
                    'phone' => $phone,
                    'email' => $this->input('mail') ? $this->input('mail') : null,
                    'password' => Hash::make($this->input('password')),
                    'is_admin' => $this->input('role')['id'] === 1 ? true : false,
                ]), function (User $user) {
                    $user->profile()->create([
                        'address' => $this->has('home_address') ? $this->input('home_address') : null,
                        'state' => $this->has('city') ? $this->input('city') : null,
                        'country' => $this->has('country_of_origin') ? $this->input('country_of_origin') : null,
                        'profession' => $this->has('profession') ? $this->input('profession') : null,
                        'ip' => $this->ip(),
                        'agent' => $this->userAgent()
                    ]);
                    $user->account()->create([]);
                    $user->ownedTeams()->save(Team::forceCreate([
                        'user_id' => $user->id,
                        'name' => explode(' ', $user->name, 2)[0] . "'s Team",
                        'personal_team' => true,
                    ]));
                });
            });

            return back();
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Operation failed. Please try again']);
        }
    }
}

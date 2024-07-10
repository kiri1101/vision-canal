<?php

namespace App\Actions\Fortify;

use App\Http\Traits\Helpers;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, Helpers;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'unique:users,phone'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $phone =  $this->removeSpaceBetweenStringChar(trim($input['phone']));

        return DB::transaction(function () use ($input, $phone) {
            return tap(User::create([
                'uuid' => Str::uuid(),
                'name' => $input['name'],
                'phone' => $phone,
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'is_admin' => true
            ]), function (User $user) {
                $this->createTeam($user);
                $user->profile()->create([]);
                $user->account()->create([]);
            });
        });
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

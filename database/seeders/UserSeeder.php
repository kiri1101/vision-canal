<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            return tap(User::create([
                'uuid' => Str::uuid(),
                'name' => 'Super Admin',
                'phone' => '690000000',
                'email' => 'admin@admin.com',
                'password' => Hash::make('123123123'),
                'is_admin' => true
            ]), function (User $user) {
                $user->profile()->create([]);
                $user->account()->create([]);
                $user->ownedTeams()->save(Team::forceCreate([
                    'user_id' => $user->id,
                    'name' => explode(' ', $user->name, 2)[0] . "'s Team",
                    'personal_team' => true,
                ]));
            });
        });
    }
}

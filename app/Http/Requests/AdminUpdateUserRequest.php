<?php

namespace App\Http\Requests;

use App\Http\Traits\Helpers;
use Exception;
use App\Rules\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class AdminUpdateUserRequest extends FormRequest
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
            'role' => ['required', new Role],
            'full_name' => 'required|string',
            'mail' => collect($this->input('mail'))->isNotEmpty() ? 'required|email' : '',
            'phone_number' => 'required|string|unique:users,phone',
            'home_address' => collect($this->input('home_address'))->isNotEmpty() ? 'required|string' : '',
            'city' => collect($this->input('city'))->isNotEmpty() ? 'required|string' : '',
            'country_of_origin' => collect($this->input('country_of_origin'))->isNotEmpty() ? 'required|string' : '',
            'profession' => collect($this->input('profession'))->isNotEmpty() ? 'required|string' : '',
            'password' => collect($this->input('mail'))->isNotEmpty() ? 'required|string|confirmed|min:4' : '',
            'password_confirmation' => collect($this->input('mail'))->isNotEmpty() ? 'required|string|min:4' : '',
        ];
    }


    public function updateUserAccount(): RedirectResponse
    {
        try {
            $phone =  $this->removeSpaceBetweenStringChar(trim($this->input('phone_number')));

            $user = User::userWithPhone($phone)->first();

            $user->update([
                'name' => $this->input('full_name'),
                'email' => $this->input('mail'),
                'phone' => $phone,
                'is_admin' => $this->input('role')['id'] === 1 ? true : false,
            ]);

            if (collect($this->input('password'))->isNotEmpty()) {
                $user->update([
                    'password' => Hash::make($this->input('password'))
                ]);
            }

            $user->profile()->update([
                'address' => $this->input('home_address'),
                'state' => $this->input('city'),
                'country' => $this->input('country_of_origin'),
                'profession' => $this->input('profession'),
                'ip' => $this->ip(),
                'agent' => $this->userAgent()
            ]);

            return back();
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Operation failed. Please try again']);
        }
    }
}

<?php

namespace App\Http\Requests;

use App\Http\Traits\Helpers;
use Exception;
use App\Rules\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Http\FormRequest;

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

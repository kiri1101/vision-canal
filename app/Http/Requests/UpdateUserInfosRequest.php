<?php

namespace App\Http\Requests;

use Exception;
use App\Models\User;
use App\Http\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserInfosRequest extends FormRequest
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
            'name' => 'required|string|min:5|max:255',
            'email' => strlen($this->input('email')) > 0 ? 'required|email:rfc,dns' : '',
            'phone' => 'required|string',
            'address' => strlen($this->input('address')) > 0 ? 'required|string' : '',
            'city' => strlen($this->input('city')) > 0 ? 'required|string' : '',
            'country' => strlen($this->input('country')) > 0 ? 'required|string' : '',
            'profession' => strlen($this->input('profession')) > 0 ? 'required|string' : '',
            'profile_pic_path' => strlen($this->input('profile_pic_path')) > 0 ? 'required|string' : '',
            'religion' => strlen($this->input('religion')) > 0 ? 'required|string' : ''
        ];
    }


    public function update(User $user): JsonResponse
    {
        try {
            $user->update([
                'name' => $this->input('name'),
                'phone' => $this->removeSpaceBetweenStringChar(trim($this->input('phone'))),
                'email' => $this->input('email'),
                'profile_photo_path' => $this->input('profile_pic_path')
            ]);

            $user->profile()->update([
                'profession' => $this->input('profession'),
                'religion' => $this->input('religion'),
                'address' => $this->input('address'),
                'country' => $this->input('country'),
                'state' => $this->input('city')
            ]);

            info('user update infos: ', [
                'user' => $user
            ]);

            return $this->successResponse('user information updated!', [
                'user' => new UserResource($user)
            ]);
        } catch (Exception $e) {
            return $this->errorResponse('User information update failed!');
        }
    }
}

<?php

namespace App\Http\Requests;

use Exception;
use App\Models\User;
use App\Http\Traits\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UploadRequest extends FormRequest
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
            'file' => 'required|file',
            'type' => 'required|string|in:photo,doc'
        ];
    }

    public function store(User $user): JsonResponse
    {
        DB::beginTransaction();

        $photo_store_path = $user->uuid . '/profiles';

        $doc_store_path = $user->uuid . '/documents';

        try {
            if ($this->input('type') === 'photo') {
                $path = $this->file('file')->store($photo_store_path);
            } else {
                $path = $this->file('file')->store($doc_store_path);
            }

            info('saving photo');

            $user->update([
                'profile_photo_path' => $path
            ]);

            DB::commit();

            return $this->successResponse(Lang::get('messages.success.file_uploaded', [], 'en'), [
                'file_url' => $path
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return $this->errorResponse('File upload failed!');
        }
    }
}

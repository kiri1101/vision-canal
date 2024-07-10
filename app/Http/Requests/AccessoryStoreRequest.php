<?php

namespace App\Http\Requests;

use Exception;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Str;
use App\Http\Traits\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class AccessoryStoreRequest extends FormRequest
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
            'articleId' => 'required|string|exists:articles,uuid'
        ];
    }

    public function store(User $user): JsonResponse
    {
        DB::beginTransaction();

        try {
            $article = Article::articleByUUID($this->input('articleId'))->first();

            if (collect($article)->isEmpty()) {
                return $this->errorResponse('Invalid article ID');
            } else {
                $user->orders()->create([
                    'uuid' => Str::uuid(),
                    'article_id' => $article->id,
                ]);
            }

            DB::commit();
            return $this->successResponse('Order registered successfully');
        } catch (Exception $e) {
            Log::error('Failed to save ACCESSORY ORDER: ', ['message' => $e->getMessage()]);

            return $this->errorResponse('Operation failed. Please try again');
        }
    }
}

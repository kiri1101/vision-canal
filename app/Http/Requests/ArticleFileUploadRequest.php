<?php

namespace App\Http\Requests;

use Exception;
use App\Models\Article;
use App\Http\Traits\Helpers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class ArticleFileUploadRequest extends FormRequest
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
            'file' => 'required|file|mimes:jpg,png,jpeg'
        ];
    }

    public function saveFile(Article $article): JsonResponse
    {
        $filePath = 'articles/' . $article->uuid . '.png';

        $uploadedFilePath = env('APP_URL') . '/storage/articles/' . $article->uuid . '.png';

        try {
            // check if file exists with filename in storage
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);

                $this->file('file')->storeAs(
                    'public/articles',
                    $article->uuid . '.png'
                );

                $this->insertRecord($article, $uploadedFilePath);

                return $this->successResponse('Upload successful', [
                    'file_path' => $uploadedFilePath
                ]);
            } else {
                $this->file('file')->storeAs(
                    'public/articles',
                    $article->uuid . '.png'
                );

                $this->insertRecord($article, $uploadedFilePath);

                return $this->successResponse('Upload successful', [
                    'file_path' => $uploadedFilePath
                ]);
            }
        } catch (Exception $e) {
            Log::emergency('Failed to upload a file for an article with ID: ' . $article->id, [
                'trace' => $e->getMessage()
            ]);

            return $this->errorResponse('Operation failed! try again');
        }
    }

    private function insertRecord(Article $article, string $path): void
    {
        $article->update(['img_path' => $path]);
    }
}

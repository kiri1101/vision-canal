<?php

namespace App\Http\Requests;

use App\Http\Traits\Helpers;
use App\Models\Article;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class UpdateArticleRequest extends FormRequest
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
            'title' => 'required|string',
            'desc' => 'required|string',
            'prize' => 'required|string',
        ];
    }

    public function store(Article $article): RedirectResponse
    {
        try {
            $article->update([
                'title' => $this->input('title'),
                'desc' => $this->input('desc'),
                'prize' => $this->input('prize')
            ]);

            return back();
        } catch (Exception $e) {
            Log::emergency('Failed to update an article with ID: ' . $article->id, [
                'trace' => $e->getMessage()
            ]);

            return back()->withErrors(['Article update failed! Try again']);
        }
    }
}

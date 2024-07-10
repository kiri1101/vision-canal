<?php

namespace App\Http\Requests;

use Exception;
use App\Models\User;
use App\Models\Setting;
use App\Http\Traits\Helpers;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;

class UpdateSocialLinkRequest extends FormRequest
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
            'type' => 'required|in:whatsapp,facebook,instagram,youtube',
            'content' => 'required|string'
        ];
    }

    public function update(): RedirectResponse
    {
        try {
            $setting = Setting::first();

            if ($this->input('type') === 'whatsapp') {
                $setting->update(['whatsapp' => $this->input('content')]);
            } elseif ($this->input('type') === 'facebook') {
                $setting->update(['facebook' => $this->input('content')]);
            } elseif ($this->input('type') === 'youtube') {
                $setting->update(['home_page_video' => $this->input('content')]);
            } else {
                $setting->update(['instagram' => $this->input('content')]);
            }

            return back();
        } catch (Exception $e) {
            Log::emergency('Failed to update settings record', [
                'trace' => $e->getMessage()
            ]);

            return back()->withErrors(['File upload failed! Try again']);
        }
    }
}

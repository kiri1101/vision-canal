<?php

namespace App\Http\Controllers\Api\Settings;

use App\Models\User;
use App\Models\Article;
use App\Models\Country;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;
use App\Http\Controllers\BaseController;
use App\Http\Requests\AuthorizationTokenRequest;
use App\Http\Resources\TransactionResource;
use Illuminate\Support\Str;

class SettingController extends BaseController
{
    public function getAuthorization(AuthorizationTokenRequest $authorizationTokenRequest): JsonResponse
    {
        return $authorizationTokenRequest->saveUserDeviceInfo();
    }

    public function getSettings(): JsonResponse
    {
        $setting = Setting::all();

        return $this->successResponse(Lang::get('messages.success.default_response', [], 'en'), [
            'services' => Service::all()->map(fn ($service) => [
                'service_name' => $service->name,
                'profile_pic_path' => $service->banner_path,
                'service_route' => $service->route_name,
                'service_alt' => $service->alt
            ]),
            'countries' => Country::all()->map(fn ($country) => [
                'id' => $country->slug,
                'label' => $country->name,
                'image_url' => env('APP_URL') . "/public/" . $country->img_path
            ]),
            // 'options' => Category::isActive()->isSpecial()->get()->map(fn ($option) => [
            //     'id' => $option->uuid,
            //     'name' => $option->name,
            //     'value' => $option->fee
            // ]),
            'offers' => Category::isActive()->isNotSpecial()->get()->map(fn ($offer) => [
                'id' => Str::uuid(),
                'name' => "{$offer->name} ({$offer->fee})",
                'label' => $offer->name,
                'value' => $offer->uuid,
                'fee' => $offer->fee
            ]),
            'payment_methods' => PaymentMethod::all()->map(fn ($payment) => [
                'id' => Str::uuid(),
                'label' => $payment->name,
                'value' => $payment->uuid,
                'method_banner_path' => env('APP_URL') . "/public/" . $payment->img_path,
                'method_banner_alt' => $payment->img_alt
            ]),
            'settings' => [
                'home_video_link' => $setting->value('home_page_video'),
                'whatsappLink' => is_null($setting->value('whatsapp')) ? '' : $setting->value('whatsapp'),
                'facebookLink' => is_null($setting->value('facebook')) ? '' : $setting->value('facebook'),
                'instagramLink' => is_null($setting->value('instagram')) ? '' : $setting->value('instagram')
            ],
            'articles' => Article::all()->map(fn ($article) => [
                'id' => $article->uuid,
                'article_pic_url' => $article->img_path,
                'article_title' => $article->title,
                'article_description' => $article->desc,
                'article_prize' => $article->prize
            ])
        ]);
    }

    public function transactionList(User $user)
    {
        $transactions = Transaction::transactionsByUser($user->id)->latest()->get();

        return $this->successResponse(Lang::get('messages.success.default_response', [], 'en'), [
            'transactions' => TransactionResource::collection($transactions),
            'total' => $user->account()->value('balance')
        ]);
    }
}

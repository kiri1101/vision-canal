<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminSubscriptionRenewalRequest;
use App\Http\Requests\AdminSubscriptionRequest;
use App\Http\Requests\AdminTransactionsRequest;
use App\Http\Requests\ArticleFileUploadRequest;
use App\Http\Requests\OrderListRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Requests\SupportListRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Requests\UpdateSocialLinkRequest;
use App\Http\Traits\Helpers;
use App\Models\Article;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    use Helpers;

    public function index()
    {
        return Inertia::render('Admin/Settings/Settings', [
            'settings' => Setting::all()->map(fn ($setting) => [
                'homeBanner' => $setting->home_page_video,
                'whatsappLink' => is_null($setting->whatsapp) ? '' : $setting->whatsapp,
                'facebookLink' => is_null($setting->facebook) ? '' : $setting->facebook,
                'instagramLink' => is_null($setting->instagram) ? '' : $setting->instagram
            ])->first(),
            'articles' => Article::all()->map(fn ($article) => [
                'id' => $article->uuid,
                'image' => $article->img_path,
                'prize' => $article->prize,
                'description' => $article->desc,
                'title' => $article->title
            ])
        ]);
    }

    public function updateSocialLink(UpdateSocialLinkRequest $request): RedirectResponse
    {
        return $request->update();
    }

    public function articlesFileUpload(Article $article, ArticleFileUploadRequest $request): JsonResponse
    {
        return $request->saveFile($article);
    }

    public function updateArticle(Article $article, UpdateArticleRequest $request): RedirectResponse
    {
        return $request->store($article);
    }

    public function transactionList(AdminTransactionsRequest $request)
    {
        return $request->search();
    }

    public function subscriptionList(AdminSubscriptionRequest $request)
    {
        return $request->search();
    }

    public function subscriptionRenewalList(AdminSubscriptionRenewalRequest $request)
    {
        return $request->search();
    }

    public function supportList(SupportListRequest $request): JsonResponse
    {
        return $request->search();
    }

    public function orderList(OrderListRequest $request)
    {
        return $request->search();
    }

    public function updateOrder(Order $order)
    {
        $order->update([
            'status' => true
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\BaseController;
use App\Http\Requests\AuthorizationTokenRequest;
use App\Models\Country;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class SettingController extends BaseController
{
    public function getAuthorization(AuthorizationTokenRequest $authorizationTokenRequest): JsonResponse
    {
        return $authorizationTokenRequest->saveUserDeviceInfo();
    }

    public function getSettings(): JsonResponse
    {
        return $this->successResponse(Lang::get('messages.success.default_response', [], 'en'), [
            'services' => Service::all()->map(fn ($service) => [
                'service_name' => $service->name,
                'profile_pic_path' => $service->banner_path,
                'service_route' => $service->route_name,
                'service_alt' => $service->alt
            ]),
            'countries' => Country::all()->map(fn ($country) => [
                'label' => $country->name
            ])
        ]);
    }
}

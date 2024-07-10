<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccessoryStoreRequest;
use App\Http\Requests\SearchAccountRequest;
use App\Http\Requests\UploadRequest;
use App\Http\Requests\SupportStoreRequest;
use App\Http\Requests\TopUpRequest;
use App\Http\Requests\UpdateUserInfosRequest;

class SupportController extends Controller
{
    public function supportStore(SupportStoreRequest $request): JsonResponse
    {
        return $request->store();
    }

    public function saveAccessory(User $user, AccessoryStoreRequest $request): JsonResponse
    {
        return $request->store($user);
    }

    public function editUser(User $user, UpdateUserInfosRequest $request): JsonResponse
    {
        return $request->update($user);
    }

    public function fileUpload(User $user, UploadRequest $request): JsonResponse
    {
        return $request->store($user);
    }

    public function searchAccount(SearchAccountRequest $request): JsonResponse
    {
        return $request->search();
    }

    public function topUpAccount(User $user, TopUpRequest $request)
    {
        return $request->cashIn($user);
    }
}

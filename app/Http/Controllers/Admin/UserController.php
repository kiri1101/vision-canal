<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCreateUserRequest;
use App\Http\Requests\AdminDeleteUserRequest;
use App\Http\Requests\AdminUpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\Helpers;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    use Helpers;

    public function index(): Response
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => UserResource::collection(User::all())
        ]);
    }

    public function createUser(AdminCreateUserRequest $request): RedirectResponse
    {
        return $request->createNewUser();
    }

    public function updateUser(AdminUpdateUserRequest $request): RedirectResponse
    {
        return $request->updateUserAccount();
    }

    public function deleteUser(User $user): JsonResponse
    {
        $user->delete();

        return $this->successResponse('Account deleted');
    }
}

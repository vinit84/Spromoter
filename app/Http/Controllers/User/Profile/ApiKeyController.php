<?php

namespace App\Http\Controllers\User\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Profile\StoreApiKeyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiKeyController extends Controller
{
    public function index()
    {
        $apiKeys = Auth::user()->tokens()->paginate(10);

        return view('user.profile.api-key', [
            'apiKeys' => $apiKeys,
        ]);
    }

    public function store(StoreApiKeyRequest $request)
    {
        $token = Auth::user()->createToken($request->validated('name'));

        return success(trans('API Key Created Successfully'), data: [
            'token' => $token->plainTextToken,
        ]);
    }
}

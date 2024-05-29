<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\StoreRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use mysql_xdevapi\Schema;

class RegisterController extends Controller
{
    public function register(StoreRegisterRequest $request)
    {
        $user = User::create($request->validated() + [
            'username' => $this->generateUsername($request->validated('first_name'), $request->validated('last_name')),
            'password' => bcrypt($request->validated('password')),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // Create store
        $store = $user->stores()->create([
            "name" => $request->validated('store_name'),
            "logo" => $request->validated('store_logo'),
            "url" => $request->validated('store_url'),
        ]);

        return apiSuccess(trans("Registered successfully"), [
            'api_key' => $token,
            'app_id' => $store->uuid,
        ]);
    }

    private function generateUsername(string $first_name, string $last_name, $number = null): string
    {
        $username = strtolower($first_name[0] . $last_name);
        $username = preg_replace('/[^a-z0-9]/', '', $username);
        $username = preg_replace('/[0-9]/', '', $username);
        $username = substr($username, 0, 20);
        $username = $username . $number;

        if (User::where('username', $username)->exists()) {
            return $this->generateUsername($first_name, $last_name, rand(0, 9));
        }

        return $username;
    }
}

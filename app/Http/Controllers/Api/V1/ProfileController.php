<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function checkCredentials(Request $request)
    {
        $exists = Store::whereUuid($request->input('app_id'))->exists();

        if ($exists){
            return apiSuccess(trans('The given data was valid'));
        }else{
            return apiError(trans('The given data was invalid'), params: [
                'errors' => [
                    'app_id' => 'The given APP ID is invalid.'
                ]
            ]);
        }
    }
}

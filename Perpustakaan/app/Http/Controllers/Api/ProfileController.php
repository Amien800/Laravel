<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'age' => 'required|integer',
            'bio' => 'required|string',
            'address' => 'required|string',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $currentUser = auth()->user();
        $profileData = Profile::updateOrCreate(
            ['user_id' => $currentUser->id],
            [
                'age' => $request->age,
                'address' => $request->address,
                'bio' => $request->bio,
                'user_id' => $currentUser->id,
            ]
        );

        return response()->json(
            [
                'message' => 'Berhasil Update Profile User',
                'data' => $profileData,
            ],
            201
        );
    }
}

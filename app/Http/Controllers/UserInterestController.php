<?php

namespace App\Http\Controllers;

use app\Helpers\ResponseFormatter;
use App\Models\UserInterest;
use Illuminate\Http\Request;

class UserInterestController extends Controller
{
    function store(Request $request)
    {
        $userInterest = UserInterest::where('category_id', $request->category_id)?->where('user_id', $request->user()->id)?->first() ?? false;

        if ($userInterest) {
            return ResponseFormatter::response(404, 'error', "user interest sudah ada");
        }

        $userInterestData = [
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
        ];

        UserInterest::create($userInterestData);

        return ResponseFormatter::response(200, 'success', $userInterestData);
    }

    function destroy(Request $request)
    {
        $userInterest = UserInterest::where('id', $request->id)?->first() ?? false;

        if (!$userInterest) {
            return ResponseFormatter::response(404, 'error', "user interest tidak ada");
        }

        $userId = UserInterest::where('id', $request->id)->first()->user_id;

        if ($userId != $request->user()->id) {
            return ResponseFormatter::response(
                404,
                'error',
                'anda tidak terautentikasi'
            );
        }

        UserInterest::where('id', $request->id)->delete();

        return ResponseFormatter::response(200, 'success', $userInterest);
    }
}

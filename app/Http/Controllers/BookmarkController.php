<?php

namespace App\Http\Controllers;

use app\Helpers\ResponseFormatter;
use App\Http\Resources\BookmarkDetailResource;
use App\Models\Bookmark;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    function index(Request $request){
        $bookmark = Bookmark::where('user_id', $request->user_id)->get();

        return ResponseFormatter::response(200, 'success', BookmarkDetailResource::collection($bookmark));
    }

    function store(Request $request){
        Bookmark::create([
            'user_id' => $request->user_id,
            'article_id' => $request->article_id
        ]);

        $bookmark = Bookmark::where('user_id', $request->user_id)->where('article_id', $request->article_id)->first();

        return ResponseFormatter::response(200, 'success', new BookmarkDetailResource($bookmark));
    }

    function destroy(Request $request){}
}

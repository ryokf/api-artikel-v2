<?php

namespace App\Http\Controllers;

use app\Helpers\ResponseFormatter;
use App\Http\Resources\BookmarkDetailResource;
use App\Models\Article;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    function index(Request $request){
        if($request->user()->id == null){
            return ResponseFormatter::response(404, 'error', "anda belum login");
        }

        $bookmark = Bookmark::where('user_id', $request->user()->id)->paginate(20);

        return ResponseFormatter::response(200, 'success', BookmarkDetailResource::collection($bookmark));
    }

    function store(Request $request){
        if($request->user()->id == null){
            return ResponseFormatter::response(404, 'error', "anda belum login");
        }

        $article = Article::where('id', $request->article_id)?->first() ?? false;

        if(!$article){
            return ResponseFormatter::response(404, 'error', "artikel tidak ada");
        }

        $bookmarkAdded = Bookmark::where('article_id', $request->article_id)->where('user_id', $request->user()->id)?->first() ?? false;

        if($bookmarkAdded){
            return ResponseFormatter::response(404, 'error', "Bookmark sudah ada");
        }

        Bookmark::create([
            'user_id' => $request->user()->id,
            'article_id' => $request->article_id
        ]);

        $bookmark = Bookmark::where('user_id', $request->user()->id)->where('article_id', $request->article_id)->first();

        return ResponseFormatter::response(200, 'success', new BookmarkDetailResource($bookmark));
    }

    function destroy(Request $request){
        if($request->user()->id == null){
            return ResponseFormatter::response(404, 'error', "anda belum login");
        }
        $bookmark =  Bookmark::where('id',$request->id)->first() ?? false;

        if(!$bookmark){
            return ResponseFormatter::response(404, 'error', "artikel tidak ada");
        }

        Bookmark::where('id',$request->id)->delete();

        return ResponseFormatter::response(200, 'success', new BookmarkDetailResource($bookmark));
    }
}

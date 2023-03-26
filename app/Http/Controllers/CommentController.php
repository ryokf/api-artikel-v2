<?php

namespace App\Http\Controllers;

use app\Helpers\ResponseFormatter;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    function store(Request $request){
        $request->validate([
            'article_id' => 'required',
            'comment' => 'required|max:255',
        ]);

        $commentData = [
            'user_id' => $request->user()->id,
            'article_id' => $request->article_id,
            'comment' => $request->comment
        ];

        Comment::create($commentData);

        return ResponseFormatter::response(200, 'success', $commentData);
    }

    function update(Request $request){
        $comment = Comment::where('id', $request->id)?->first() ?? false;

        if(!$comment){
            return ResponseFormatter::response(404, 'error', "comment tidak ada");
        }

        $commentData = [
            'comment' => $request->comment,
            'updated' => true
        ];

        $userId = Comment::where('id', $request->id)->first()->user_id;

        if ($userId != $request->user()->id) {
            return ResponseFormatter::response(
                404,
                'error',
                'anda tidak terautentikasi'
            );
        }

        Comment::where('id', $request->id)->update($commentData);

        return ResponseFormatter::response(200, 'success', $commentData);
    }

    function destroy(Request $request){
        $comment = Comment::where('id', $request->id)?->first() ?? false;

        if(!$comment){
            return ResponseFormatter::response(404, 'error', "comment tidak ada");
        }

        $userId = Comment::where('id', $request->id)->first()->user_id;

        if ($userId != $request->user()->id) {
            return ResponseFormatter::response(
                404,
                'error',
                'anda tidak terautentikasi'
            );
        }

        Comment::where('id', $request->id)->delete();

        return ResponseFormatter::response(200, 'success', $comment);
    }
}

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
            'user_id' => 'required',
            'comment' => 'required|max:255',
        ]);

        $commentData = [
            'user_id' => $request->user_id,
            'article_id' => $request->article_id,
            'comment' => $request->comment
        ];

        Comment::create($commentData);

        return ResponseFormatter::response(200, 'success', $commentData);
    }

    function update(Request $request){}

    function destroy(Request $request){}
}

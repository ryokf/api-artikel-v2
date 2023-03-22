<?php


namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use app\Helpers\ResponseFormatter;
use App\Http\Resources\ArticleDetailResource;
use App\Http\Resources\UserInterestResource;
use App\Models\UserInterest;

class ArticleController extends Controller
{
    function index(Request $request)
    {
        if ($request->slug || $request->id) {
            $article = Article::where('slug', $request->slug)?->orWhere('id', $request->id)->first();;

            if ($article) {

                if (auth()->user()?->id != null) {
                    $already_seen = Article::Where('user_id', auth()->user()->id)->where('article_id', $request->id)?->first() ?? false;

                    if(!$already_seen){
                        $already_seen->create([
                            'user_id' => auth()->user()->id,
                            'article_id' => $request->id
                        ]);
                    }
                }
                return ResponseFormatter::response(
                    200,
                    'success',
                    new ArticleDetailResource($article)
                );
            } else {
                return ResponseFormatter::response(
                    404,
                    'error',
                    'data tidak ditemukan'
                );
            }
        }

        if ($request->order == 'latest') {
            $articles = Article::latest();
        } else {
            $articles = Article::oldest();
        }

        if ($request->title) {
            $articles = $articles->where('title', 'LIKE', '%' . $request->title . '%');
        }
        if ($request->tags) {
            $articles = $articles->where('title', 'LIKE', '%' . $request->tags . '%');
        }
        if ($request->category) {
            $articles = $articles->where('category_id', $request->category);
        }
        if ($request->location) {
            $articles = $articles->where('location', $request->location);
        }

        if (count($articles->get()) != 0) {
            if(auth('sanctum')->check()){
                if(($request->title == null || $request->tags == null)){
                    return ResponseFormatter::response(
                        200,
                        'success',
                        UserInterestResource::collection(UserInterest::where('user_id', $request->user_id)->paginate(50))
                    );
                }
            }
            return ResponseFormatter::response(
                200,
                'success',
                ArticleResource::collection($articles->paginate(50))
            );
        } else {
            return ResponseFormatter::response(
                404,
                'error',
                'data tidak ditemukan'
            );
        }
    }

    function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:articles|max:255',
            'content' => 'required',
            'prologue' => 'max:512',
            'category_id' => 'required',
            'writer_id' => 'required',
        ]);

        $titleExp = explode(" ", $request->title);
        $slug = strtolower(implode("-", $titleExp));

        Article::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'prologue' => $request->prologue,
            'category_id' => $request->category_id,
            'writer_id' => $request->writer_id,
            'thumbnail' => $request->thumbnail,
            'tags' => $request->tags,
        ]);

        $newArticle = Article::where('slug', $slug)->orWhere('id', $request->id);

        if (count($newArticle->get()) != 0) {
            return ResponseFormatter::response(
                200,
                'success',
                new ArticleDetailResource($newArticle->first())
            );
        } else {
            return ResponseFormatter::response(
                404,
                'error',
                'data tidak ditemukan'
            );
        }
    }

    function update(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'prologue' => 'max:512',
            'category_id' => 'required',
        ]);

        $titleExp = explode(" ", $request->title);
        $slug = strtolower(implode("-", $titleExp));

        Article::Where('id', $request->id)->update([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'prologue' => $request->prologue,
            'category_id' => $request->category_id,
            'thumbnail' => $request->thumbnail,
            'tags' => $request->tags,
        ]);

        $updatedArticle = Article::where('id', $request->id);

        if (count($updatedArticle->get()) != 0) {
            return ResponseFormatter::response(
                200,
                'success',
                new ArticleDetailResource($updatedArticle->first())
            );
        } else {
            return ResponseFormatter::response(
                404,
                'error',
                'data tidak ditemukan'
            );
        }
    }

    function destroy(Request $request)
    {
        $deletedArticle = Article::where('id', $request->id)->get();

        if (count($deletedArticle) != 0) {

            Article::where('id', $request->id)->delete();

            return ResponseFormatter::response(
                200,
                'success',
                ArticleDetailResource::collection($deletedArticle)
            );
        } else {
            return ResponseFormatter::response(
                404,
                'error',
                'data tidak ditemukan'
            );
        }
    }
}

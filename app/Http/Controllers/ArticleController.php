<?php


namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use app\Helpers\ResponseFormatter;
use App\Http\Resources\ArticleDetailResource;
use App\Http\Resources\CategoryMemberResource;
use App\Http\Resources\UserInterestResource;
use App\Models\Category;
use App\Models\UserInterest;
use App\Models\Viewers;

class ArticleController extends Controller
{
    function index(Request $request)
    {
        // jika request berupa id akan return isi lengkap article
        if ($request->id) {
            $article = Article::Where('id', $request->id)?->first() ?? false;

            // jika article ditemukan
            if ($article) {
                // cek apakah user sudah melihat article yang dibuka
                $already_seen = Viewers::Where('user_id', $request->user()->id)
                    ->where('article_id', $request->id)
                    ?->first()
                    ?? false;

                // jika belum akan ditambah sebagai viewer dari artikel yang dibuka
                if (!$already_seen) {
                    Viewers::create([
                        'user_id' => $request->user()->id,
                        'article_id' => $request->id
                    ]);
                }

                // jika sudah langsung return article tanpa menambah viewer
                return ResponseFormatter::response(
                    200,
                    'success',
                    new ArticleDetailResource($article)
                );
            }
            // jika article tidak ditemukan
            else {
                return ResponseFormatter::response(
                    404,
                    'error',
                    'data tidak ditemukan'
                );
            }
        }
        //------------------------------------------------------------------------------------//

        // jika tidak ada request id

        if ($request->order == 'latest') {
            $articles = Article::latest();
        }
        else if($request->order == 'oldest'){
            $articles = Article::oldest();
        }
        else {
            $articles = Article::inRandomOrder();
        }

        // jika ada request title atau tags, akan dilakukan pencarian
        if ($request->title) {
            $articles = $articles->where('title', 'LIKE', '%' . $request->title . '%');
        }
        if ($request->tags) {
            $articles = $articles->where('title', 'LIKE', '%' . $request->tags . '%');
        }
        // jika ada request category, akan filter catagory
        if ($request->category) {
            $articles = $articles->where('category_id', $request->category);
        }
        // jika ada request lokasi, akan filter lokasi
        if ($request->location) {
            $articles = $articles->where('location', $request->location);
        }

        if (count($articles->get()) != 0) {

            if (($request->title == null && $request->tags == null)) {

                $userInterest = UserInterest::where('user_id', $request->user()->id)->get();

                // output homepage
                return ResponseFormatter::response(
                    200,
                    'success',
                    UserInterestResource::collection($userInterest)
                );
            }

            // output search
            return ResponseFormatter::response(
                200,
                'success',
                ArticleResource::collection($articles->paginate(50))
            );
        } else {
            // jika artikel yang di search atau filter tidak ada
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
            'writer_id' => 'required',
        ]);

        if ($request->writer_id != Article::where('id', $request->id)->first()->writer_id) {
            return ResponseFormatter::response(
                404,
                'error',
                'anda tidak terautentikasi'
            );
        }

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

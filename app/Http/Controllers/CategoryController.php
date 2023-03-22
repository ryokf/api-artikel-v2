<?php

namespace App\Http\Controllers;

use app\Helpers\ResponseFormatter;
use App\Http\Resources\CategoryMemberResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function index(Request $request){
        if($request->id){
            $category = Category::where('id', $request->id)?->with('articles')->get();

            if (count($category) != 0) {
                return ResponseFormatter::response(
                    200,
                    'success',
                    CategoryMemberResource::collection($category)
                );
            } else {
                return ResponseFormatter::response(
                    404,
                    'error',
                    'data tidak ditemukan'
                );
            }
        }

        $categories =  Category::all();
        return ResponseFormatter::response(
            200,
            'success',
            CategoryMemberResource::collection($categories));
    }
}

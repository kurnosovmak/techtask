<?php

namespace App\Http\Controllers;

use App\Http\Resources\Category as CategoryJson;
use App\Models\Category;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use SoftDeletes;

    function index(Request $request){
        $categories = Category::all();
        return CategoryJson::collection($categories);
    }

    function show($id){
        $categories = Category::find($id);
        if($categories != null)
            return new CategoryJson($categories);
        else
            return ['error'=>'category not find'];
    }

    function store(Request $request){

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $product = new Category();
        $product->title = $request->input('title');
        $product->save();

        return $product->id;

    }

    function update(Request $request){

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $category = Category::find($request->category);
        if($category != null) {
            if ($request->input('title') != '')
                $category->title = $request->input('title');
            $category->save();
        }else{
            return [''=>'category not find'];
        }

        return $category->id;

    }

    function destroy($id){
        $p = Category::find($id);
        if($id != null) {
            $pc = ProductCategory::getProductsByIdCategory($id);
            if(count($pc) == 0)
            {
                Category::destroy($id);
                return 'true';
            }else{
                return [
                    'error' => 'in products used this category',
                    'data_products'=>$pc
                    ];
            }

        }
        return 'false';
    }

}

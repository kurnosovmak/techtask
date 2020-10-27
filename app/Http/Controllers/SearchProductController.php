<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchProductController extends Controller
{
    function search(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255|min:3',//Product +
            'id_category' => 'array',//CategoryProduct +
            'title_category' => 'string|max:255',//CategoryProduct +
            'min_price' => 'numeric|min:0',//Product +
            'max_price' => 'numeric|min:0',//Product +
            'published' => 'boolean',//Product +
            'remote' => 'boolean',//Product +
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $where = [];

        if($request->input('title') != ''){
            $where[count($where)] = ['title', 'like', '%'.$request->input('title').'%'];
        }
        if($request->input('max_price') != ''){
            $where[count($where)] = ['price', '<=', $request->input('max_price') ];
        }
        if($request->input('min_price') != ''){
            $where[count($where)] = ['price', '>=', $request->input('min_price') ];
        }
        if($request->input('published') != ''){
            $where[count($where)] = ['published', $request->input('published') ];
        }
        if($request->input('remote') != '' && $request->input('remote')){
            $where[count($where)] = ['deleted_at', '<>','null'];
        }else{
            $where[count($where)] = ['deleted_at', '=',null];
        }




        $rez = Product::withTrashed()->where($where)->get();
        $rez2 =[];
        if ($request->input('id_category') != '' or $request->input('title_category') != '') {

            $cid = [];
            if($request->input('title_category') != '') {
                $categories = Category::where('title', 'like', '%' . $request->input('title_category') . '%')->get();
                $cid = [];
                foreach ($categories as $category)
                    $cid[count($cid)] = $category->id;
            }
            // all id category by title_category and id_category
            $idCategories = array_unique(array_merge($cid,$request->input('id_category')??[]), SORT_REGULAR);

            // get product with category
            foreach ($idCategories as $id_category) {

                if(Category::isset($id_category)) {
                    $productsWithCategoryBiIdCategory = ProductCategory::getProductsByIdCategory($id_category);
                    foreach ($productsWithCategoryBiIdCategory as $item)
                        foreach ($rez as $value) {
                            if ($value->id == $item->products_id)
                                $rez2[count($rez2)] = $value;
                        }
                    $rez = $rez2;
                    $rez2 = [];
                }
            }
        }
        return \App\Http\Resources\Product::collection($rez);
    }
}

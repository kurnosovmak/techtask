<?php

namespace App\Http\Controllers;
use App\Http\Resources\Product as ProductJson;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    function index(Request $request){
        $products = Product::all();
        return ProductJson::collection($products);
    }

    function show($id){
        $product = Product::find($id);

        if($product != null)
            return new ProductJson($product);
        else
            return ['error'=>'product not find'];
    }

    function store(Request $request){

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'price' => 'required|numeric|min:0',
            'mini_description' => 'required|max:255',
            'full_description' => 'required',
            'published' => 'required|boolean',
            'categories' => 'array',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $product = new Product();
        $product->title = $request->input('title');
        $product->price = $request->input('price');
        $product->mini_description = $request->input('mini_description');
        $product->full_description = $request->input('full_description');
        $product->published = $request->input('published');
        $product->save();
        foreach ($request->input('categories') as $item){
            if(Category::isset($item)) {
                if(!ProductCategory::ProductHaveCategory($product->id,$item)) {
                    $pc = new ProductCategory();
                    $pc->products_id = $product->id;
                    $pc->categories_id = $item;
                    $pc->save();
                }
            }else{

                ProductCategory::where('products_id',$product->id)->delete();
                $product->forceDelete();
                return['error'=>'category with this id does not created'];
            }
        }
        return $product->id;

    }

    function update(Request $request){

        $validator = Validator::make($request->all(), [
            'title' => 'max:255',
            'price' => 'numeric|min:0',
            'mini_description' => 'max:255',
            'full_description' => 'string',
            'published' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $product = Product::find($request->product);
        if($product != null) {
            if ($request->input('title') != '')
                $product->title = $request->input('title');
            if ($request->input('price') != '')
                $product->price = $request->input('price');
            if ($request->input('mini_description') != '')
                $product->mini_description = $request->input('mini_description');
            if ($request->input('full_description') != '')
                $product->full_description = $request->input('full_description');
            if ($request->input('published') != '')
                $product->published = $request->input('published');
            $product->save();
        }else{
            return ['error'=>'product not find'];
        }
        ProductCategory::where('products_id',$product->id)->delete();
        foreach ($request->input('categories') as $item){
            if(Category::isset($item) ) {
                if(!ProductCategory::ProductHaveCategory($product->id,$item)) {
                    $pc = new ProductCategory();
                    $pc->products_id = $product->id;
                    $pc->categories_id = $item;
                    $pc->save();
                }
            }else{

                ProductCategory::where('products_id',$product->id)->delete();
                $product->forceDelete();
                return['error'=>'category with this id does not created'];
            }
        }

        return $product->id;

    }

    function destroy($product){
        $p = Product::find($product);
        if($product != null) {
            Product::destroy($product);
            return 'true';
        }
        return 'false';
    }

}

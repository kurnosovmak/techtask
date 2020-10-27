<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\False_;

class ProductCategory extends Model
{


    static function getCategoriesByIdProduct($id_product){
        return  ProductCategory::where([
            'products_id'=>$id_product,
        ])->get();
    }

    static function getProductsByIdCategory($id_category){
        return  ProductCategory::where([
            'categories_id'=>$id_category,
        ])->get();
    }

    static function ProductHaveCategory($id_product,$id_category){
        $p = ProductCategory::where([
            'categories_id'=>$id_category,
            'products_id'=>$id_product,
        ])->get();

        if(count($p) == 0)return false;
        return true;
    }

}

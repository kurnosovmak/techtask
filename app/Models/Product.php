<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
use SoftDeletes;

    public static function create( string $title, float $price, string $mini_description, string $full_description,bool $published)
    {
        $product = new Product();
        $product->title = $title;
        $product->price = $price;
        $product->mini_description = $mini_description;
        $product->full_description = $full_description;
        $product->published = $published;
        $product->save();
        return $product;
    }
}

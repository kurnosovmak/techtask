<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i=0;$i<5;$i++){
            Product::create($faker->text(),$faker->randomFloat(2,10,1000000),$faker->text(40),$faker->text(),false);
        }
    }
}

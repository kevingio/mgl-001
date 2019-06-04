<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Propolis 6ml',
                'qty' => 30
            ],
            [
                'name' => 'Propolis 30ml',
                'qty' => 10
            ],
            [
                'name' => 'Propolis 55ml',
                'qty' => 50
            ],
            [
                'name' => 'HGH',
                'qty' => 30
            ],
        ];
        Product::truncate();
        foreach ($data as $value) {
            Product::create($value);
        }
    }
}

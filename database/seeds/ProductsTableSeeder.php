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
            [
                'name' => 'Cleanser',
                'qty' => 20
            ],
            [
                'name' => 'Platinum Serum',
                'qty' => 20
            ],
            [
                'name' => 'Anti Aging',
                'qty' => 20
            ],
            [
                'name' => 'Day Cream',
                'qty' => 10
            ],
            [
                'name' => 'Night Cream',
                'qty' => 10
            ],
            [
                'name' => 'Lip Cream Red',
                'qty' => 10
            ],
            [
                'name' => 'Lip Cream Rosewood',
                'qty' => 10
            ],
            [
                'name' => 'Lip Cream Nude',
                'qty' => 10
            ],
            [
                'name' => 'Lip Cream Blush',
                'qty' => 10
            ],
            [
                'name' => 'Lip Gel Magic',
                'qty' => 10
            ],
            [
                'name' => 'Two Cake Light',
                'qty' => 10
            ],
            [
                'name' => 'Two Cake Natural',
                'qty' => 10
            ],
            [
                'name' => 'Paket A',
                'qty' => 1
            ],
            [
                'name' => 'Paket B',
                'qty' => 1
            ],
            [
                'name' => 'Paket C',
                'qty' => 1
            ],

        ];
        Product::truncate();
        foreach ($data as $value) {
            Product::create($value);
        }
    }
}

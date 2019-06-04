<?php

use Illuminate\Database\Seeder;
use App\Models\PostingType;

class PostingTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [ 'name' => 'Member Baru Gold' ],
            [ 'name' => 'Tambah Lot Gold' ],
            [ 'name' => 'Member Baru Silver' ],
            [ 'name' => 'Tambah Lot Silver' ],
            [ 'name' => 'Retail' ]
        ];

        PostingType::truncate();
        foreach ($data as $value) {
            PostingType::create($value);
        }
    }
}

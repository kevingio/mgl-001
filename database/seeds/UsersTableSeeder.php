<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        User::create([
            'name' => 'Vinda Claudya',
            'username' => 'vinclaud',
            'password' => bcrypt('password')
        ]);
    }
}

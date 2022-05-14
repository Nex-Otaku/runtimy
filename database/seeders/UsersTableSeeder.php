<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(
            [
                'email' => 'demo@mail.com'
            ],
            [
                'name' => 'Demo User',
                'password' => bcrypt('secret'),
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use App\Module\Admin\LkAccountRegistry;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Throwable
     */
    public function run()
    {
        LkAccountRegistry::instance()->registerDemo();
    }
}

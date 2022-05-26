<?php

namespace Database\Seeders;

use App\Module\Customer\Entities\Courier;
use Illuminate\Database\Seeder;

class CouriersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Courier::createRandom();
    }
}

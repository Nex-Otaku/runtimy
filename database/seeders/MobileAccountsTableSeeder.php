<?php

namespace Database\Seeders;

use App\Module\MobileAuth\Entities\MobileAccount;
use Illuminate\Database\Seeder;

class MobileAccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MobileAccount::createDemo();
    }
}

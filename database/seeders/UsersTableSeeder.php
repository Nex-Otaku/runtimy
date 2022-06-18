<?php

namespace Database\Seeders;

use App\Models\User;
use App\Module\PasswordAuth\Models\PasswordAccount;
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
        $user = new User();
        $user->saveOrFail();

        $passwordAccount = new PasswordAccount(
            [
                'user_id' => $user->id,
                'email' => 'demo@mail.com',
                'name' => 'Demo User',
                'password' => bcrypt('secret'),
            ]
        );

        $passwordAccount->saveOrFail();
    }
}

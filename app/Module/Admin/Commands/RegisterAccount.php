<?php

namespace App\Module\Admin\Commands;

use App\Module\Admin\Vo\Role;
use App\Module\Common\ModuleSystem;
use Illuminate\Console\Command;

class RegisterAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:register-account {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создать аккаунт для входа в ЛК';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        $name = $this->ask('Имя');
        $password = $this->ask('Пароль');
        $roleCode = $this->choice('Роль', Role::getLkChoices());
        $role = Role::fromString($roleCode);

        ModuleSystem::instance()->getCourierAccountRegistry()
                                ->registerLkAccount($name, $email, $password, $role);

        $this->info('Аккаунт создан.');

        return Command::SUCCESS;
    }
}

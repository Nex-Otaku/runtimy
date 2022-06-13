<?php

namespace App\Module\ModelMaker\Commands;

use App\Module\ModelMaker\ModelMaker;
use Illuminate\Console\Command;

class ModelMakerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model-maker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Model Maker';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $modelNameWithSpaces = $this->ask('Название модели');
        ModelMaker::instance()->make($modelNameWithSpaces);

        return 0;
    }
}

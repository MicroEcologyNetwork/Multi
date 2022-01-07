<?php

namespace MicroEcology\Multi\Console;

use MicroEcology\Multi\Multi;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class ImportCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'multi:import {extension?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import a Laravel-multi extension';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $extension = $this->argument('extension');

        if (empty($extension) || !Arr::has(Multi::$extensions, $extension)) {
            $extension = $this->choice('Please choose a extension to import', array_keys(Multi::$extensions));
        }

        $className = Arr::get(Multi::$extensions, $extension);

        if (!class_exists($className) || !method_exists($className, 'import')) {
            $this->error("Invalid Extension [$className]");

            return;
        }

        call_user_func([$className, 'import'], $this);

        $this->info("Extension [$className] imported");
    }
}

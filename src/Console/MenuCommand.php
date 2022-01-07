<?php

namespace MicroEcology\Multi\Console;

use MicroEcology\Multi\Facades\Multi;
use Illuminate\Console\Command;

class MenuCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'multi:menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show the multi menu';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $menu = Multi::menu();

        echo json_encode($menu, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), "\r\n";
    }
}

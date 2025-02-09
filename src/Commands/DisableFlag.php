<?php

namespace DynamikDev\TinyFlag\Commands;

use DynamikDev\TinyFlag\Facades\TinyFlag;
use Illuminate\Console\Command;

class DisableFlag extends Command
{
    protected $signature = 'flags:disable {name}';

    protected $description = 'Disable a flag';

    public function handle()
    {
        TinyFlag::disable($this->argument('name'));

        $this->info('Flag disabled successfully');
    }
}

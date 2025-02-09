<?php

namespace DynamikDev\TinyFlag\Commands;

use DynamikDev\TinyFlag\Facades\TinyFlag;
use Illuminate\Console\Command;

class EnableFlag extends Command
{
    protected $signature = 'flags:enable {name}';

    protected $description = 'Enable a flag';

    public function handle()
    {
        TinyFlag::enable($this->argument('name'));

        $this->info('Flag enabled successfully');
    }
}

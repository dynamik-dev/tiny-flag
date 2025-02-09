<?php

namespace DynamikDev\TinyFlag\Commands;

use DynamikDev\TinyFlag\Enums\FlagState;
use DynamikDev\TinyFlag\Facades\TinyFlag;
use DynamikDev\TinyFlag\Models\Flag;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class ListFlags extends Command
{
    protected $signature = 'flags:list {--state= : Filter by state (active/inactive)}';

    protected $description = 'List all flags and their current states';

    public function handle()
    {
        /**
         * @var Collection<int, Flag>
         */
        $flags = TinyFlag::query()->when(
            $this->option('state'),
            function ($query) {
                $state = strtolower($this->option('state'));
                $flagState = match ($state) {
                    'active', 'true', '1' => FlagState::ACTIVE,
                    'inactive', 'false', '0' => FlagState::INACTIVE,
                    default => null
                };

                if ($flagState !== null) {
                    $query->where('state', $flagState);
                }
            }
        )->get();

        if ($flags->isEmpty()) {
            $this->info('No flags found.');

            return;
        }

        $headers = ['ID', 'Flag Name', 'State'];
        $rows = $flags->map(function (Flag $flag) {
            return [
                $flag->id,
                $flag->name,
                $this->getStateAsString($flag->state),
            ];
        })->toArray();

        $this->table($headers, $rows);
    }

    public function getStateAsString(FlagState $state): string
    {
        return match ($state) {
            FlagState::ACTIVE => 'Active',
            FlagState::INACTIVE => 'Inactive',
        };
    }
}

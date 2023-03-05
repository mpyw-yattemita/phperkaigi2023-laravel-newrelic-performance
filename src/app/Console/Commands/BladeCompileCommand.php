<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;

class BladeCompileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blade:compile';

    public function handle(): void
    {
        Blade::compile(resource_path('views/index.blade.php'));
    }
}

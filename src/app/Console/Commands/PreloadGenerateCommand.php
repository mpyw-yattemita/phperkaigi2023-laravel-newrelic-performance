<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

class PreloadGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'preload:generate';

    public function handle(): void
    {
        // Get files in these paths
        $files = collect(Finder::create()->in(config('opcache.preload_directories'))
            ->name('*.php')
            ->ignoreUnreadableDirs()
            ->notContains('#!/usr/bin/env php')
            ->exclude(config('opcache.exclude'))
            ->files()
            ->followLinks());

        $buf = "<?php\n\n";
        foreach ($files as $file) {
            $buf .= 'opcache_compile_file(__DIR__ . ' . var_export('/..' . substr((string)$file, strlen(base_path())), true) . ");\n";
        }

        file_put_contents(base_path('bootstrap/preload.php'), $buf);
    }
}

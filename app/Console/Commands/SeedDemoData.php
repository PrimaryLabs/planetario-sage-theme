<?php

namespace App\Console\Commands;

use App\PostTypes\BlogPost;
use App\PostTypes\CompanyEvent;
use Illuminate\Console\Command;

class SeedDemoData extends Command
{
    protected $signature   = 'seed:demo {--force : Re-seed even if already seeded}';
    protected $description = 'Insert demo CompanyEvent and BlogPost records from StaticData';

    public function handle(): int
    {
        $force = (bool) $this->option('force');

        foreach ([
            'CompanyEvents' => fn () => CompanyEvent::seed($force),
            'BlogPosts'     => fn () => BlogPost::seed($force),
        ] as $label => $seeder) {
            $result = $seeder();
            if ($result['skipped'] ?? false) {
                $this->info("{$label}: already seeded (use --force to re-seed)");
            } else {
                $this->info("{$label}: created {$result['created']} records");
            }
        }

        return self::SUCCESS;
    }
}

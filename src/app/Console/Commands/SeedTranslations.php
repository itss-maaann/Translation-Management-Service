<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Translation;
use Illuminate\Console\Command;

class SeedTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tms:seed-translations {count=100000 : Number of translations to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the translations table with fake data and random tags for load testing';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count     = (int) $this->argument('count');
        $batchSize = 1000;

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($created = 0; $created < $count; $created += $batchSize) {
            $batch = min($batchSize, $count - $created);

            Translation::factory()
                ->count($batch)
                ->create();

            $bar->advance($batch);
        }

        $bar->finish();
        $this->newLine();
        $this->info("Seeded {$count} translations successfully.");

        return Command::SUCCESS;
    }
}

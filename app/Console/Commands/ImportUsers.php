<?php

namespace App\Console\Commands;

use App\Services\RandomUserImporter\CustomerImporterInterface;
use Illuminate\Console\Command;
use Throwable;

class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-users {--results=100} {--nationality=AU}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(protected CustomerImporterInterface $importer)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $this->info('Importing users');

            $results = (int) $this->option('results');
            $nationality = (string) $this->option('nationality');

            $this->importer->importUsers($results, $nationality);

            $this->info('Users imported successfully');
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Jobs\OzonFbsListJob;
use Illuminate\Console\Command;

class OzonFbsListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ozon:fbs {dateFrom?} {dateTo?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ozon - Список отправлений ФБС';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        OzonFbsListJob::dispatch($this->argument('dateFrom'), $this->argument('dateTo'));
    }
}

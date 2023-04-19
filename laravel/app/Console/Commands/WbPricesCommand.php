<?php

namespace App\Console\Commands;

use App\Jobs\WbPricesJob;
use Illuminate\Console\Command;

class WbPricesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:prices {dateFrom?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'WB - Получение информации о ценах';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        WbPricesJob::dispatch($this->argument('dateFrom'));
    }
}

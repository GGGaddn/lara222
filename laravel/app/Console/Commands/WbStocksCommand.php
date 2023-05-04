<?php

namespace App\Console\Commands;

use App\Jobs\WbStocksJob;
use Illuminate\Console\Command;

class WbStocksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:stocks {dateFrom?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'WB - Получение информации об остатках';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        WbStocksJob::dispatch($this->argument('dateFrom'));
    }
}

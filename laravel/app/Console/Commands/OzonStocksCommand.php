<?php

namespace App\Console\Commands;

use App\Jobs\OzonStocksJob;
use Illuminate\Console\Command;

class OzonStocksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ozon:stocks {last_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ozon - Информация о количестве товаров';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        OzonStocksJob::dispatch($this->argument('last_id'));
    }
}

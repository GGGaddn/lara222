<?php

namespace App\Console\Commands;

use App\Jobs\WbOrdersJob;
use Illuminate\Console\Command;

class WbOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:orders {dateFrom?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'WB - Получение информации о заказах';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        WbOrdersJob::dispatch($this->argument('dateFrom'));
    }
}

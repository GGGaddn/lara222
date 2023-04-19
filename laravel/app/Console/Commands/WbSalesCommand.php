<?php

namespace App\Console\Commands;

use App\Jobs\WbSalesJob;
use Illuminate\Console\Command;

class WbSalesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:sales {dateFrom?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'WB - Получение информации о продажах';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        WbSalesJob::dispatch($this->argument('dateFrom'));
    }
}

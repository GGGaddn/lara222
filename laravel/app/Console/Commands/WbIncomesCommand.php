<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Jobs\WbIncomesJob;

class WbIncomesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:incomes {dateFrom?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'WB - Получение информации о поставках';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        WbIncomesJob::dispatch($this->argument('dateFrom'));
    }
}

<?php

namespace App\Console\Commands;

use App\Jobs\WbSalesReportJob;
use Illuminate\Console\Command;

class WbReportDetailByPeriodCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:report {dateFrom?} {dateTo?} {rrdid?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'WB - Отчет о продажах по реализации';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        WbSalesReportJob::dispatch($this->argument('dateFrom'));
    }
}

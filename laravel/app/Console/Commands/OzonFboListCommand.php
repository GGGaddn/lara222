<?php

namespace App\Console\Commands;

use App\Jobs\OzonFboListJob;
use Illuminate\Console\Command;

class OzonFboListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ozon:fbo {dateFrom?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ozon - Список отправлений ФБО';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        OzonFboListJob::dispatch($this->argument('dateFrom'));
    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Libraries\Ozon;
use App\Models\OzonStocks;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class OzonStocksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $last_id;
    private $ozon;
    
    /**
     * Create a new job instance.
     */
    public function __construct($last_id = "") {
        $this->last_id = $last_id;
        $this->ozon = new Ozon;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = $this->ozon->stocks($this->last_id);
        if($response['result']) {
            $count = 0;
            foreach($response['data']['result']['items'] as $item) { 
                $ozon_item = new OzonStocks();
                $ozon_item->date = Carbon::now();
                if(isset($item['product_id'])) $ozon_item->product_id = $item['product_id'];                       
                if(isset($item['offer_id'])) $ozon_item->offer_id = $item['offer_id'];   
                if(isset($item['stocks'])) foreach($item['stocks'] as $stock) {
                    if($stock['type'] == 'fbo') {
                        $ozon_item->fbo_present = $stock['present'];   
                        $ozon_item->fbo_reserved = $stock['reserved'];   
                    }

                    if($stock['type'] == 'fbs') {
                        $ozon_item->fbs_present = $stock['present'];   
                        $ozon_item->fbs_reserved = $stock['reserved'];   
                    }
                }               
                $ozon_item->save();
                $count++;
            }

            Log::info('[OZON] Информация о количестве товаров успешна загружена! Кол-во записей: ' . $count);
        }
    }
}

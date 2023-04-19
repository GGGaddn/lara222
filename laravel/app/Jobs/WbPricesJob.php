<?php

namespace App\Jobs;

use App\Libraries\Wildberries;
use App\Models\WbPrices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class WbPricesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $dateFrom;
    private $wb;

    /**
     * Create a new job instance.
     */
    public function __construct($dateFrom = null) {
        $this->dateFrom = $dateFrom;
        $this->wb = new Wildberries;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = $this->wb->prices($this->dateFrom);

        if($response['result']) {
            $count = 0;
            foreach($response['data'] as $item) { 
                $wb_item = new WbPrices();
                if(isset($item['nmId'])) $wb_item->nm_id = $item['nmId'];
                if(isset($item['price'])) $wb_item->price = $item['price'];
                if(isset($item['discount'])) $wb_item->discount = $item['discount'];
                if(isset($item['promoCode'])) $wb_item->promo_code = $item['promoCode'];
                $wb_item->date = new Carbon($this->dateFrom);
                $wb_item->save();
                $count++;
            }

            Log::info("Информация о ценах за " . $this->dateFrom . ' успешно загружена! Кол-во записей: ' . $count);
        } elseif($response['many_requests']) {
            //Если вернется tooManyRequests, перезапускаем Job через 1 минуту
            WbPricesJob::dispatch($this->dateFrom)->delay(now()->addMinutes(1));
        }    
    }
}

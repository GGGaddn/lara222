<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Log;
use App\Libraries\Wildberries;
use App\Models\WbStocks;
use Illuminate\Support\Carbon;

class WbStocksJob implements ShouldQueue
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
        $response = $this->wb->stocks($this->dateFrom);

        if($response['result']) {
            $count = 0;
            foreach($response['data'] as $item) { 
                $wb_item = new WbStocks();
                $wb_item->date = new Carbon($this->dateFrom);
                if(isset($item['lastChangeDate'])) $wb_item->last_change_date = $item['lastChangeDate'];
                if(isset($item['supplierArticle'])) $wb_item->supplier_article = $item['supplierArticle'];
                if(isset($item['techSize'])) $wb_item->tech_size = $item['techSize'];
                if(isset($item['barcode'])) $wb_item->barcode = $item['barcode'];
                if(isset($item['quantity'])) $wb_item->quantity = $item['quantity'];
                if(isset($item['isSupply'])) $wb_item->is_supply = $item['isSupply'];
                if(isset($item['isRealization'])) $wb_item->is_realization = $item['isRealization'];
                if(isset($item['quantityFull'])) $wb_item->quantity_full = $item['quantityFull'];
                if(isset($item['warehouseName'])) $wb_item->warehouse_name = $item['warehouseName'];
                if(isset($item['nmId'])) $wb_item->nm_id = $item['nmId'];
                if(isset($item['subject'])) $wb_item->subject = $item['subject'];
                if(isset($item['category'])) $wb_item->category = $item['category'];
                if(isset($item['daysOnSite'])) $wb_item->days_on_site = $item['daysOnSite'];
                if(isset($item['brand'])) $wb_item->brand = $item['brand'];
                if(isset($item['SCCode'])) $wb_item->sc_code = $item['SCCode'];
                if(isset($item['Price'])) $wb_item->Price = $item['Price'];
                if(isset($item['Discount'])) $wb_item->Discount = $item['Discount'];                  
                $wb_item->save();
                $count++;
            }

            Log::info("Информация о продажах за " . $response['params']['dateFrom'] . ' успешно загружена! Кол-во записей: ' . $count);
        } elseif($response['many_requests']) {
            //Если вернется tooManyRequests, перезапускаем Job через 1 минуту
            WbStocksJob::dispatch($this->dateFrom)->delay(now()->addMinutes(1));
        }    
    }
}

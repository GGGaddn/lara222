<?php

namespace App\Jobs;

use App\Models\WbOrders;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Libraries\Wildberries;
use Illuminate\Support\Carbon;

class WbOrdersJob implements ShouldQueue
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
        $response = $this->wb->orders($this->dateFrom);

        if($response['result']) {
            $count = 0;
            foreach($response['data'] as $item) { 
                $wb_item = new WbOrders();
                if(isset($item['date'])) $wb_item->date = $item['date'];
                if(isset($item['lastChangeDate'])) $wb_item->last_change_date = $item['lastChangeDate'];
                if(isset($item['supplierArticle'])) $wb_item->supplier_article = $item['supplierArticle'];
                if(isset($item['techSize'])) $wb_item->tech_size = $item['techSize'];
                if(isset($item['barcode'])) $wb_item->barcode = $item['barcode'];
                if(isset($item['totalPrice'])) $wb_item->total_price = $item['totalPrice'];
                if(isset($item['discountPercent'])) $wb_item->discount_percent = $item['discountPercent'];
                if(isset($item['warehouseName'])) $wb_item->warehouse_name = $item['warehouseName'];
                if(isset($item['oblast'])) $wb_item->oblast = $item['oblast'];
                if(isset($item['incomeID'])) $wb_item->income_id = $item['incomeID'];
                if(isset($item['odid'])) $wb_item->odid = $item['odid'];
                if(isset($item['nmId'])) $wb_item->nm_id = $item['nmId'];
                if(isset($item['subject'])) $wb_item->subject = $item['subject'];
                if(isset($item['category'])) $wb_item->category = $item['category'];
                if(isset($item['brand'])) $wb_item->brand = $item['brand'];
                if(isset($item['isCancel'])) {
                    $wb_item->is_cancel = $item['isCancel'];
                    $wb_item->cancel_dt = new Carbon($item['cancel_dt']);
                } 
                
                if(isset($item['gNumber'])) $wb_item->g_number = $item['gNumber'];
                if(isset($item['sticker'])) $wb_item->sticker = $item['sticker'];
                if(isset($item['srid'])) $wb_item->srid = $item['srid'];
                $wb_item->save();
                $count++;
            }

            Log::info("Информация о поставках за " . $response['params']['dateFrom'] . ' успешно загружена! Кол-во записей: ' . $count);
        } elseif($response['many_requests']) {
            //Если вернется tooManyRequests, перезапускаем Job через 1 минуту
            WbOrdersJob::dispatch($this->dateFrom)->delay(now()->addMinutes(1));
        }        
    }
}

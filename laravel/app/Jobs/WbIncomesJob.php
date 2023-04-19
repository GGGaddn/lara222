<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Libraries\Wildberries;
use App\Models\WbIncomes;
use Illuminate\Support\Facades\Log;

class WbIncomesJob implements ShouldQueue
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
        $response = $this->wb->incomes($this->dateFrom);

        if($response['result']) {
            $count = 0;
            foreach($response['data'] as $item) { 
                $wb_item = new WbIncomes;
                if(isset($item['incomeId'])) $wb_item->income_id = $item['incomeId'];
                if(isset($item['number'])) $wb_item->number = $item['number'];
                if(isset($item['date'])) $wb_item->date = $item['date'];
                if(isset($item['lastChangeDate'])) $wb_item->last_change_date = $item['lastChangeDate'];
                if(isset($item['supplierArticle'])) $wb_item->supplier_article = $item['supplierArticle'];
                if(isset($item['techSize'])) $wb_item->tech_size = $item['techSize'];
                if(isset($item['barcode'])) $wb_item->barcode = $item['barcode'];
                if(isset($item['quantity'])) $wb_item->quantity = $item['quantity'];
                if(isset($item['totalPrice'])) $wb_item->total_price = $item['totalPrice'];
                if(isset($item['dateClose'])) $wb_item->date_close = $item['dateClose'];
                if(isset($item['warehouseName'])) $wb_item->warehouse_name = $item['warehouseName'];
                if(isset($item['nmId'])) $wb_item->nm_id = $item['nmId'];
                if(isset($item['status'])) $wb_item->status = $item['status'];
                $wb_item->save();
                $count++;
            }

            Log::info("Информация о поставках за " . $response['params']['dateFrom'] . ' успешно загружена! Кол-во записей: ' . $count);
        } elseif($response['many_requests']) {
            //Если вернется tooManyRequests, перезапускаем Job через 1 минуту
            WbIncomesJob::dispatch($this->dateFrom)->delay(now()->addMinutes(1));
        }        
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Libraries\Wildberries;
use App\Libraries\Ozon;
use App\Models\OzonFboList;
use App\Models\OzonStocks;
use App\Models\WbIncomes;
use App\Models\WbOrders;
use App\Models\WbPrices;
use App\Models\WbReportDetailByPeriod;
use App\Models\WbSales;
use App\Models\WbStocks;
use Illuminate\Support\Carbon;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $wb = new Wildberries;
        $ozon = new Ozon;
        // $response = $wb->reportDetailByPeriod('01.03.2023', '15.03.2023');
        $response = $ozon->fbo_list();
        // dd($response);
        if($response['result']) {
            $count = 0;
            foreach($response['data']['result'] as $item) { 
                $ozon_item = new OzonFboList();
                if(isset($item['order_id'])) $ozon_item->order_id = $item['order_id'];                                   
                if(isset($item['order_number'])) $ozon_item->order_number = $item['order_number'];                                   
                if(isset($item['posting_number'])) $ozon_item->posting_number = $item['posting_number'];                                   
                if(isset($item['status'])) $ozon_item->status = $item['status'];                                   
                if(isset($item['cancel_reason_id'])) $ozon_item->cancel_reason_id = $item['cancel_reason_id'];                                   
                if(isset($item['created_at'])) $ozon_item->ozon_created_at = new Carbon($item['created_at']);                                  
                if(isset($item['in_process_at'])) $ozon_item->ozon_in_process_at = new Carbon($item['in_process_at']);                                   
                if(isset($item['products'])) $ozon_item->products = $item['products'];                                   
                if(isset($item['analytics_data'])) $ozon_item->analytics_data = $item['analytics_data'];                                   
                if(isset($item['financial_data'])) $ozon_item->financial_data = $item['financial_data'];                                   
                if(isset($item['additional_data'])) $ozon_item->additional_data = $item['additional_data'];                                   
                $ozon_item->save();
                $count++;
            }
        }
        $this->info('[OZON] Информация о количестве товаров успешна загружена! Кол-во записей: ' . $count);
    }
}
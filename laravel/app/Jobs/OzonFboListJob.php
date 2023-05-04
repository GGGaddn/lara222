<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Libraries\Ozon;
use App\Models\OzonPostingFbo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class OzonFboListJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $date_from;
    private $ozon;
    
    /**
     * Create a new job instance.
     */
    public function __construct($date_from = null) {
        $this->date_from = $date_from;
        $this->ozon = new Ozon;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = $this->ozon->fbo_list($this->date_from);
        if($response['result']) {
            $count = 0;
            foreach($response['data']['result'] as $item) { 
                if(isset($item['products'])) {
                    foreach($item['products'] as $index => $product) {
                        $ozon_item = new OzonPostingFbo();
                        $ozon_item->order_id = $item['order_id'];                                   
                        $ozon_item->order_number = $item['order_number'];                                   
                        $ozon_item->posting_number = $item['posting_number'];                                   
                        $ozon_item->status = $item['status'];                                   
                        $ozon_item->cancel_reason_id = $item['cancel_reason_id'];                                   
                        $ozon_item->posting_created_at = new Carbon($item['created_at']);     
                        $ozon_item->additional_data = $item['additional_data'];  
                        
                        $ozon_item->sku = $product['sku'];
                        $ozon_item->name = $product['name'];
                        $ozon_item->quantity = $product['quantity'];
                        $ozon_item->offer_id = $product['offer_id'];
                        $ozon_item->price = $product['price'];
                        $ozon_item->digital_codes = $product['digital_codes'];
                        $ozon_item->currency_code = $product['currency_code'];

                        $ozon_item->region = $item['analytics_data']['region'];
                        $ozon_item->city = $item['analytics_data']['city'];
                        $ozon_item->delivery_type = $item['analytics_data']['delivery_type'];
                        $ozon_item->is_premium = $item['analytics_data']['is_premium'];
                        $ozon_item->payment_type_group_name = $item['analytics_data']['payment_type_group_name'];
                        $ozon_item->warehouse_id = $item['analytics_data']['warehouse_id'];
                        $ozon_item->warehouse_name = $item['analytics_data']['warehouse_name'];
                        $ozon_item->is_legal = $item['analytics_data']['is_legal'];

                        $ozon_item->commission_amount = $item['financial_data']['products'][$index]['commission_amount'];
                        $ozon_item->commission_percent = $item['financial_data']['products'][$index]['commission_percent'];
                        $ozon_item->payout = $item['financial_data']['products'][$index]['payout'];
                        $ozon_item->product_id = $item['financial_data']['products'][$index]['product_id'];
                        $ozon_item->old_price = $item['financial_data']['products'][$index]['old_price'];
                        $ozon_item->total_discount_value = $item['financial_data']['products'][$index]['total_discount_value'];
                        $ozon_item->total_discount_percent = $item['financial_data']['products'][$index]['total_discount_percent'];
                        $ozon_item->actions = $item['financial_data']['products'][$index]['actions'];
                        $ozon_item->picking = $item['financial_data']['products'][$index]['picking'];
                        $ozon_item->client_price = $item['financial_data']['products'][$index]['client_price'];

                        $ozon_item->cluster_from = $item['financial_data']['cluster_from'];
                        $ozon_item->cluster_to = $item['financial_data']['cluster_to'];

                        $ozon_item->marketplace_service_item_fulfillment = $item['financial_data']['products'][$index]['item_services']['marketplace_service_item_fulfillment'];
                        $ozon_item->marketplace_service_item_pickup = $item['financial_data']['products'][$index]['item_services']['marketplace_service_item_pickup'];
                        $ozon_item->marketplace_service_item_dropoff_pvz = $item['financial_data']['products'][$index]['item_services']['marketplace_service_item_dropoff_pvz'];
                        $ozon_item->marketplace_service_item_dropoff_sc = $item['financial_data']['products'][$index]['item_services']['marketplace_service_item_dropoff_sc'];
                        $ozon_item->marketplace_service_item_dropoff_ff = $item['financial_data']['products'][$index]['item_services']['marketplace_service_item_dropoff_ff'];
                        $ozon_item->marketplace_service_item_direct_flow_trans = $item['financial_data']['products'][$index]['item_services']['marketplace_service_item_direct_flow_trans'];
                        $ozon_item->marketplace_service_item_return_flow_trans = $item['financial_data']['products'][$index]['item_services']['marketplace_service_item_return_flow_trans'];
                        $ozon_item->marketplace_service_item_deliv_to_customer = $item['financial_data']['products'][$index]['item_services']['marketplace_service_item_deliv_to_customer'];
                        $ozon_item->marketplace_service_item_return_not_deliv_to_customer = $item['financial_data']['products'][$index]['item_services']['marketplace_service_item_return_not_deliv_to_customer'];
                        $ozon_item->marketplace_service_item_return_part_goods_customer = $item['financial_data']['products'][$index]['item_services']['marketplace_service_item_return_part_goods_customer'];
                        $ozon_item->marketplace_service_item_return_after_deliv_to_customer = $item['financial_data']['products'][$index]['item_services']['marketplace_service_item_return_after_deliv_to_customer'];                               
                                                        
                        $ozon_item->save();
                        $count++;
                    }
                }
            }

            Log::info('[OZON] Список отправлений ФБО успешно загружен! Кол-во записей: ' . $count);
        }
    }
}

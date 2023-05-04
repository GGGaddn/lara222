<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Libraries\Wildberries;
use App\Models\WbSalesReport;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class WbSalesReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $dateFrom;
    public $dateTo;
    public $rrdid;
    private $wb;

    /**
     * Create a new job instance.
     */
    public function __construct($dateFrom = null, $dateTo = null, $rrdid = 0) {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->rrdid = $rrdid;
        $this->wb = new Wildberries;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = $this->wb->reportDetailByPeriod($this->dateFrom, $this->dateTo, $this->rrdid);

        if($response['result']) {
            $count = 0;
            if($response['result']) {
                $count = 0;
                foreach($response['data'] as $item) { 
                    $wb_item = new WbSalesReport();
                    if(isset($item['realizationreport_id'])) $wb_item->realizationreport_id = $item['realizationreport_id'];             
                    if(isset($item['date_from'])) $wb_item->date_from = new Carbon($item['date_from']);             
                    if(isset($item['date_to'])) $wb_item->date_to = new Carbon($item['date_to']);             
                    if(isset($item['create_dt'])) $wb_item->create_dt = new Carbon($item['create_dt']);             
                    if(isset($item['suppliercontract_code'])) $wb_item->suppliercontract_code = $item['suppliercontract_code'];             
                    if(isset($item['rrd_id'])) $wb_item->rrd_id = $item['rrd_id'];             
                    if(isset($item['gi_id'])) $wb_item->gi_id = $item['gi_id'];             
                    if(isset($item['subject_name'])) $wb_item->subject_name = $item['subject_name'];             
                    if(isset($item['nm_id'])) $wb_item->nm_id = $item['nm_id'];             
                    if(isset($item['brand_name'])) $wb_item->brand_name = $item['brand_name'];             
                    if(isset($item['sa_name'])) $wb_item->sa_name = $item['sa_name'];             
                    if(isset($item['ts_name'])) $wb_item->ts_name = $item['ts_name'];             
                    if(isset($item['barcode'])) $wb_item->barcode = $item['barcode'];             
                    if(isset($item['doc_type_name'])) $wb_item->doc_type_name = $item['doc_type_name'];             
                    if(isset($item['quantity'])) $wb_item->quantity = $item['quantity'];             
                    if(isset($item['retail_price'])) $wb_item->retail_price = $item['retail_price'];             
                    if(isset($item['retail_amount'])) $wb_item->retail_amount = $item['retail_amount'];             
                    if(isset($item['sale_percent'])) $wb_item->sale_percent = $item['sale_percent'];             
                    if(isset($item['commission_percent'])) $wb_item->commission_percent = $item['commission_percent'];             
                    if(isset($item['office_name'])) $wb_item->office_name = $item['office_name'];             
                    if(isset($item['supplier_oper_name'])) $wb_item->supplier_oper_name = $item['supplier_oper_name'];             
                    if(isset($item['order_dt'])) $wb_item->order_dt = new Carbon($item['order_dt']);             
                    if(isset($item['sale_dt'])) $wb_item->sale_dt = new Carbon($item['sale_dt']);             
                    if(isset($item['rr_dt'])) $wb_item->rr_dt = new Carbon($item['rr_dt']);             
                    if(isset($item['shk_id'])) $wb_item->shk_id = $item['shk_id'];             
                    if(isset($item['retail_price_withdisc_rub'])) $wb_item->retail_price_withdisc_rub = $item['retail_price_withdisc_rub'];             
                    if(isset($item['delivery_amount'])) $wb_item->delivery_amount = $item['delivery_amount'];             
                    if(isset($item['return_amount'])) $wb_item->return_amount = $item['return_amount'];             
                    if(isset($item['delivery_rub'])) $wb_item->delivery_rub = $item['delivery_rub'];             
                    if(isset($item['gi_box_type_name'])) $wb_item->gi_box_type_name = $item['gi_box_type_name'];             
                    if(isset($item['product_discount_for_report'])) $wb_item->product_discount_for_report = $item['product_discount_for_report'];             
                    if(isset($item['supplier_promo'])) $wb_item->supplier_promo = $item['supplier_promo'];             
                    if(isset($item['rid'])) $wb_item->rid = $item['rid'];             
                    if(isset($item['ppvz_spp_prc'])) $wb_item->ppvz_spp_prc = $item['ppvz_spp_prc'];             
                    if(isset($item['ppvz_kvw_prc_base'])) $wb_item->ppvz_kvw_prc_base = $item['ppvz_kvw_prc_base'];             
                    if(isset($item['ppvz_kvw_prc'])) $wb_item->ppvz_kvw_prc = $item['ppvz_kvw_prc'];             
                    if(isset($item['ppvz_sales_commission'])) $wb_item->ppvz_sales_commission = $item['ppvz_sales_commission'];             
                    if(isset($item['ppvz_for_pay'])) $wb_item->ppvz_for_pay = $item['ppvz_for_pay'];             
                    if(isset($item['ppvz_reward'])) $wb_item->ppvz_reward = $item['ppvz_reward'];             
                    if(isset($item['acquiring_fee'])) $wb_item->acquiring_fee = $item['acquiring_fee'];             
                    if(isset($item['acquiring_bank'])) $wb_item->acquiring_bank = $item['acquiring_bank'];             
                    if(isset($item['ppvz_vw'])) $wb_item->ppvz_vw = $item['ppvz_vw'];             
                    if(isset($item['ppvz_vw_nds'])) $wb_item->ppvz_vw_nds = $item['ppvz_vw_nds'];             
                    if(isset($item['ppvz_office_id'])) $wb_item->ppvz_office_id = $item['ppvz_office_id'];             
                    if(isset($item['ppvz_office_name'])) $wb_item->ppvz_office_name = $item['ppvz_office_name'];             
                    if(isset($item['ppvz_supplier_id'])) $wb_item->ppvz_supplier_id = $item['ppvz_supplier_id'];             
                    if(isset($item['ppvz_supplier_name'])) $wb_item->ppvz_supplier_name = $item['ppvz_supplier_name'];             
                    if(isset($item['ppvz_inn'])) $wb_item->ppvz_inn = $item['ppvz_inn'];             
                    if(isset($item['declaration_number'])) $wb_item->declaration_number = $item['declaration_number'];             
                    if(isset($item['bonus_type_name'])) $wb_item->bonus_type_name = $item['bonus_type_name'];             
                    if(isset($item['sticker_id'])) $wb_item->sticker_id = $item['sticker_id'];             
                    if(isset($item['site_country'])) $wb_item->site_country = $item['site_country'];             
                    if(isset($item['penalty'])) $wb_item->penalty = $item['penalty'];             
                    if(isset($item['additional_payment'])) $wb_item->additional_payment = $item['additional_payment'];             
                    if(isset($item['kiz'])) $wb_item->kiz = $item['kiz'];             
                    if(isset($item['srid'])) $wb_item->srid = $item['srid'];             
                    $wb_item->save();
                    $count++;
                }
            }

            Log::info("Отчет о продаже с " . $response['params']['dateFrom'] . ' по ' . $response['params']['dateTo']  . ' успешно загружен! Кол-во записей: ' . $count);
        } elseif($response['many_requests']) {
            //Если вернется tooManyRequests, перезапускаем Job через 1 минуту
            WbSalesReportJob::dispatch($this->dateFrom)->delay(now()->addMinutes(1));
        }    
    }
}

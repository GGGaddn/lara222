<?php
namespace App\Libraries;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use PhpParser\Node\Expr\Cast\String_;

class Ozon
{    
    /**
     *  Client-Id для осуществления запросов к OZON API
     *
     * @var String 
     */
    private $client_id;    
    /**
     * URL для осуществления запросов к OZON API
     *
     * @var String
     */
    private $api_url;  
     
    /**
     * Ключ для осуществления запросов к OZON API
     *
     * @var String
     */
    private $api_key;    
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        $this->client_id = config('services.ozon.client_id');
        $this->api_url = config('services.ozon.api_url');
        $this->api_key = config('services.ozon.api_key');
    }

    /**
     * Метод для осуществления запросов к API OZON
     *
     * @param  String $method Наименование метода для вызова
     * @param  array $params Параметры передаваемые для вызова
     * @return void
     */
    private function makeRequest(String $method, array $params = []) {   

        $response = Http::withHeaders([
            'Client-Id' => $this->client_id,
            'Api-Key' => $this->api_key,
        ])->post($this->api_url . '/' . $method, $params);

        return ['result' => $response->successful(), 'many_requests' => $response->tooManyRequests(), 'data' => $response->json(), 'params' => $params];
    }
   
    /**
     * Получение информации о количестве товаров
     *
     * @param  String|null $last_id Идентификатор последнего значения на странице. Оставьте это поле пустым при выполнении первого запроса.
     * @return mixed
     */
    public function stocks(?String $last_id = "") {
        return $this->makeRequest('v3/product/info/stocks', [
            'filter' => [
                'visibility' => 'ALL'
            ],
            'last_id' => $last_id,
            'limit' => 1000
        ]);
    }
    
    /**
     * Список отправлений ФБО
     *
     * @param  String|null $dateFrom Начало периода в формате ДД.ММ.ГГГГ
     * @return mixed
     */
    public function fbo_list(?String $dateFrom = null) {
        $dateFrom = !$dateFrom ? Carbon::now()->subDay() : new Carbon($dateFrom);
        
        return $this->makeRequest('v2/posting/fbo/list', [
            'filter' => [
                'since' => $dateFrom->toIso8601String()
            ],
            'limit' => 1000,
            'with' => [
                'analytics_data' => true,
                'financial_data' => true
            ],
        ]);
    }
    
    /**
     * Список отправлений ФБС
     *
     * @param  String|null $dateFrom Начало периода в формате ДД.ММ.ГГГГ
     * @param  String|null $dateTo Конец периода в формате ДД.ММ.ГГГГ
     * @return mixed
     */
    public function fbs_list(?String $dateFrom = null, ?String $dateTo = null) {
        $dateFrom = !$dateFrom ? Carbon::now()->subDay() : new Carbon($dateFrom);
        $dateTo = !$dateTo ? Carbon::now() : new Carbon($dateTo);
        
        return $this->makeRequest('v3/posting/fbs/list', [
            'filter' => [
                'since' => $dateFrom->toIso8601ZuluString('millisecond'),
                'to' => $dateTo->toIso8601ZuluString('millisecond'),
            ],
            'limit' => 1000,
            'with' => [
                'analytics_data' => true,
                'financial_data' => true
            ],
        ]);
    }
   
    
}

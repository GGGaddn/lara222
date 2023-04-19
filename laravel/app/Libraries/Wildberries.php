<?php
namespace App\Libraries;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class Wildberries
{    
    /**
     *  URL для получения данных поставщика
     *
     * @var String 
     */
    private $suppliers_url;    
    /**
     * Ключ для осуществления запросов поставщика
     *
     * @var String
     */
    private $suppliers_key;    
    /**
     * URL для получения данных статистики
     *
     * @var String
     */
    private $statistics_url;    
    /**
     * Ключ для осуществления запросов статистики
     *
     * @var String
     */
    private $statistics_key;    

    /**
     * Массив методов, которые обращаются к API поставщиков
     *
     * @var array
     */
    private $suppliers_methods;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        $this->suppliers_url = config('services.wildberries.suppliers.url');
        $this->suppliers_key = config('services.wildberries.suppliers.key');
        $this->statistics_url = config('services.wildberries.statistics.url');
        $this->statistics_key = config('services.wildberries.statistics.key');
        $this->suppliers_methods = ['info'];
    }
   
    /**
     * Получение информации о поставках.
     *
     * @param  String|null $dateFrom Дата в формате ДД.ММ.ГГГГ за которую получить статистику 
     * @return mixed
     */
    public function incomes(?String $dateFrom = null) { 
        $dateFrom = !$dateFrom ? Carbon::now() : new Carbon($dateFrom);

        return $this->makeRequest('incomes', ['dateFrom' => $dateFrom->format('Y-m-d')]);
    }
    
    /**
     * Получение информации о ценах.
     *
     * @return void
     */
    public function prices() {
        return $this->makeRequest('info');
    }

    /**
     * Получение информации о заказах.
     *
     * @param  String|null $dateFrom Дата в формате ДД.ММ.ГГГГ за которую получить информацию о заказах
     * @return mixed
     */
    public function orders(?String $dateFrom = null) { 
        $dateFrom = !$dateFrom ? Carbon::now() : new Carbon($dateFrom);

        return $this->makeRequest('orders', ['dateFrom' => $dateFrom->format('Y-m-d')]);
    }

    /**
     * Получение информации о продажах.
     *
     * @param  String|null $dateFrom Дата в формате ДД.ММ.ГГГГ за которую получить информацию о продажах
     * @return mixed
     */
    public function sales(?String $dateFrom = null) { 
        $dateFrom = !$dateFrom ? Carbon::now() : new Carbon($dateFrom);

        return $this->makeRequest('sales', ['dateFrom' => $dateFrom->format('Y-m-d')]);
    }

    /**
     * Получение информации об остатках.
     *
     * @param  String|null $dateFrom Дата в формате ДД.ММ.ГГГГ за которую получить информацию об остатках
     * @return mixed
     */
    public function stocks(?String $dateFrom = null) { 
        $dateFrom = !$dateFrom ? Carbon::now() : new Carbon($dateFrom);

        return $this->makeRequest('stocks', ['dateFrom' => $dateFrom->format('Y-m-d')]);
    }

    /**
     * Отчет о продажах по реализации
     *
     * @param  String|null $dateFrom Дата в формате ДД.ММ.ГГГГ с которой получить информацию о продажах по реализации
     * @param  String|null $dateTo   Дата в формате ДД.ММ.ГГГГ по которую получить информацию о продажах по реализации
     * @param  int         $rrdid    Уникальный идентификатор строки отчета. Необходим для получения отчета частями
     * @return mixed 
     */
    public function reportDetailByPeriod(?String $dateFrom = null, ?String $dateTo = null, int $rrdid = 0) { 
        $dateFrom = !$dateFrom ? Carbon::now()->subDay() : new Carbon($dateFrom);
        $dateTo = !$dateTo ? Carbon::now() : new Carbon($dateTo);

        return $this->makeRequest('reportDetailByPeriod', ['dateFrom' => $dateFrom->format('Y-m-d'), 'dateTo' => $dateTo->format('Y-m-d'), 'rrdid' => $rrdid]);
    }
    
    /**
     * Метод для осуществления запросов к API WB
     *
     * @param  String $method Наименование метода для вызова
     * @param  array $params Параметры передаваемые для вызова
     * @return void
     */
    private function makeRequest(String $method, array $params = []) {   
        $url = in_array($method, $this->suppliers_methods) ? $this->suppliers_url : $this->statistics_url;
        $key = in_array($method, $this->suppliers_methods) ? $this->suppliers_key : $this->statistics_key;

        $response = Http::withHeaders([
            'Authorization' => $key,
        ])->get($url . '/' . $method, $params);

        return ['result' => $response->successful(), 'many_requests' => $response->tooManyRequests(), 'data' => $response->json(), 'params' => $params];
    }
}

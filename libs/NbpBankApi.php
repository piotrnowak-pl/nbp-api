<?php

class NbpBankApi
{

    public function bankApiRateRequst($currency,$start_date,$end_date){
        $start_date = $this->apiDateFormat($start_date);
        $end_date =  $this->apiDateFormat($end_date);

        $url = "http://api.nbp.pl/api/exchangerates/rates/a/{$currency}/{$start_date}/{$end_date}/?format=json";
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $response = curl_exec($ch);
        } catch (Exception $e) {
           throw new Exception("NBP Api connection problem...");
        }
        return json_decode($response, true);
    }

    public function getAverageRate($currency, $start_date, $end_date){
        $api_data = $this->bankApiRateRequst($currency, $start_date, $end_date);
        $total = 0;
        $average_rate = 0;

        if(isset($api_data['rates']) && is_array($api_data['rates'])){
            foreach ($api_data['rates'] as $rate) {
                $total = bcadd($total,$rate['mid'],2);
            }
            $average_rate = $total / count($api_data['rates']);
        } else{
           throw new Exception("NBP Api result is not readable or has worng format");
        }

        
        return round($average_rate,4);
    }

    public function apiDateFormat($date){
        return date('Y-m-d', strtotime($date));
    }
}

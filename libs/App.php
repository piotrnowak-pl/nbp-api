<?php

class App{

	public static $currency;
	public static $start_date;
	public static $end_date;

	public static function run(){

		Response::setHeader('Content-Type: application/json');
		
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			Response::result(['status'=>'HTTP/1.0 405 Method Not Allowed','message'=>'Error: Only GET requests are allowed.']);
		}


		self::getRequestParameters();
		self::validateGetParameters();

		
	    try {
	        $bankApi = new NbpBankApi;
	        $result = $bankApi->getAverageRate(self::$currency, self::$start_date, self::$end_date);
	    } catch (Exception $e) {
	        Response::result(['status'=>'error','message'=>$e->getMessage()]);
	    }

	    
	    Response::result(['average_price' => $result]);
	
	}

	private static function getRequestParameters(){

		$current_dir = dirname($_SERVER['SCRIPT_NAME']);
	    $request = explode('/',trim(str_replace($current_dir,'',$_SERVER['REQUEST_URI']),'/'));
	    self::$currency = $request[0]??'';
	    self::$start_date = $request[1]??'';
	    self::$end_date = $request[2]??'';
	}

	private static function validateGetParameters(){

	    if(!is_string(self::$currency) || strlen(self::$currency)<3){
	        Response::result(['status'=>'error','message'=>'First parameter: "currency" is wrong']);
	    }

	    if(empty(self::$start_date) || !Validate::isDate(self::$start_date) ){
	        Response::result(['status'=>'error','message'=>'Second parameter: "start date" has wrong value']);
	    }

	    if(empty(self::$end_date) || !Validate::isDate(self::$end_date) ){
	        Response::result(['status'=>'error','message'=>'Third parameter: "end date" has wrong value']);
	    }

	    if(strtotime(self::$start_date) > strtotime(self::$end_date)){
	    	Response::result(['status'=>'error','message'=>'Third parameter: "end date" must be greater then the second parameter "start_date"']);
	    }
	}

}

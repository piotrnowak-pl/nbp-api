<?php 

class Response{
    public static function setHeader(string $params){
        header($params);
    }

    public static function result(array $data){
        if(isset($data['status']) && $data['status']!=200){
            if(strpos($data['status'],'HTTP') === false){
                Response::setHeader("HTTP/1.1 502 Bad Gateway");    
            }else{
                Response::setHeader($data['status']);    
            }
            
        }
        echo json_encode($data);
        die;
    }
}
<?php

class Validate{
    public static function isDate($date){
        $check = date_parse($date);
        return $check['error_count'] == 0 && $check['warning_count'] == 0;
    }
}
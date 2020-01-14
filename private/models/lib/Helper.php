
<?php

class Helper{
    
    public static function arrayToObject(array $array, $className) {
        return unserialize(sprintf(
            'O:%d:"%s"%s',
            strlen($className),
            $className,
            strstr(serialize($array), ':')
        ));
    }


    
    public static function objectToObject($instance, $className) {
        return unserialize(sprintf(
            'O:%d:"%s"%s',
            strlen($className),
            $className,
            strstr(strstr(serialize($instance), '"'), ':')
        ));
    }

    public static function redirect_to($url){
        header('Location: ' . $url);
    }

    public static function is_post(){
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function is_get(){
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public static function post_val($val){
        return (isset($_POST[$val]) && (!empty($_POST[$val]))) ? trim($_POST[$val]) : null;
    }

    public static function get_val($val){
        return (isset($_GET[$val]) && (!empty($_GET[$val]))) ? trim($_GET[$val]) : null;
    }

    public static function validateEmail($email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) return "Invalid Email";
        else return null;
    }

    public static function invalid_length($key, $value, $length){
        if(strlen($value) < $length)  return ucfirst($key) . ' must be at least ' . $length . ' char long.';
        else return null;
    }

    public static function unique_code($limit){
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    public static function unique_numeric_code($limit){
        return substr(uniqid(mt_rand()), 0, $limit);
    }


	public static function days_ago($created_date){
        $ts1 = strtotime($created_date);
        $ts2 = strtotime(date(DATE_FORMAT, time()));
        $distance = floor(($ts2 - $ts1) / (24 * 60 * 60));
        return ($distance > -1) ? $distance : 0;
    }
	
	
}
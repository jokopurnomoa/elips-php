<?php
/**
 * Benchmark
 *
 * Benchmark tools
 *
 */

class Benchmark {

    private static $time;

    public static function microtime_float(){
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    public static function startTime($flagname = 'std'){
        self::$time[$flagname] = self::microtime_float();
    }

    public static function getTime($flagname = 'std', $round = 4){
        if(isset(self::$time[$flagname])){
            return round(self::microtime_float() - self::$time[$flagname], $round);
        }
        return 0;
    }

}

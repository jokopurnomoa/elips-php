<?php
/**
 * Format date library
 *
 *
 */

class FormatDate {

    static $arr_month_id = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    static $arr_month_en = array('', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    static $lang = self::EN;

    const EN = 1;
    const ID = 2;

    public static function setLanguage($lang){
        self::$lang = $lang;
    }

    public static function toYmd($date, $separator = '-'){
        $day = substr($date, 0, 2);
        $month = substr($date, 3, 2);
        $year = substr($date, 6, 4);

        return $year.$separator.$month.$separator.$day;
    }

    public static function toDmy($date, $separator = '-'){
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        return $day.$separator.$month.$separator.$year;
    }

    public static function formatDmy($date){
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        return $day.' '.(self::$lang === self::EN ? self::$arr_month_en[$month*1] : self::$arr_month_id[$month*1]).' '.$year;
    }

    public static function formatDmyHi($date){
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        $hour = substr($date, 11, 2);
        $minute = substr($date, 14, 2);

        if($date != '0000-00-00 00:00:00')
            return $day.' '.(self::$lang === self::EN ? self::$arr_month_en[$month*1] : self::$arr_month_id[$month*1]).' '.$year.' - '.$hour.':'.$minute;
        else
            return '-';
    }

    public static function formatDmyHis($date){
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        $hour = substr($date, 11, 2);
        $minute = substr($date, 14, 2);
        $second = substr($date, 17, 2);

        if($date != '0000-00-00 00:00:00')
            return $day.' '.(self::$lang === self::EN ? self::$arr_month_en[$month*1] : self::$arr_month_id[$month*1]).' '.$year.' - '.$hour.':'.$minute.':'.$second;
        else
            return '-';
    }

    public static function toDmyHi($date, $separator){
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        $hour = substr($date, 11, 2);
        $minute = substr($date, 14, 2);

        return $day.$separator.$month.$separator.$year.' '.$hour.':'.$minute;
    }

    public static function toYmdHi($date){
        $day = substr($date, 0, 2);
        $month = substr($date, 3, 2);
        $year = substr($date, 6, 4);

        $hour = substr($date, 11, 2);
        $minute = substr($date, 14, 2);

        return $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute;
    }

    public static function day($date){
        return substr($date, 8, 2);
    }

    public static function month($date){
        return substr($date, 5, 2);
    }

    public static function year($date){
        return substr($date, 0, 4);
    }

    public static function monthName($date){
        $month = substr($date, 5, 2);

        return (self::$lang === self::EN ? self::$arr_month_en[$month*1] : self::$arr_month_id[$month*1]);
    }

}

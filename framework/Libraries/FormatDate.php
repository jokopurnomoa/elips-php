<?php
/**
 * FormatDate Library
 *
 *
 */

namespace Elips\Libraries;

class FormatDate
{

    /**
     * @var array
     */
    static $arr_month_id = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

    /**
     * @var array
     */
    static $arr_month_en = array('', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

    /**
     * @var int
     */
    static $lang = self::EN;

    const EN = 1;
    const ID = 2;

    /**
     * Set Data Language
     *
     * @param $lang
     */
    public static function setLanguage($lang)
    {
        self::$lang = $lang;
    }

    /**
     * Convert From d-m-Y to Y-m-d
     *
     * @param $date
     * @param string $separator
     * @return string
     */
    public static function toYmd($date, $separator = '-')
    {
        $day = substr($date, 0, 2);
        $month = substr($date, 3, 2);
        $year = substr($date, 6, 4);

        if ($date === '00-00-0000' || $date === '' || $date === null) {
            return null;
        }
        return $year.$separator.$month.$separator.$day;
    }

    /**
     * Convert From Y-m-d to d-m-Y
     *
     * @param $date
     * @param string $separator
     * @return string
     */
    public static function toDmy($date, $separator = '-')
    {
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        if ($date === '00-00-0000' || $date === '' || $date === null) {
            return null;
        }
        return $day.$separator.$month.$separator.$year;
    }

    /**
     * Format to d-M-Y
     *
     * @param $date
     * @return string
     */
    public static function formatDmy($date)
    {
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        if ($date === '0000-00-00 00:00:00' || $date === '' || $date === null) {
            return null;
        }
        return $day.' '.(self::$lang === self::EN ? self::$arr_month_en[$month*1] : self::$arr_month_id[$month*1]).' '.$year;
    }

    /**
     * Format to d-M-Y H:i
     *
     * @param $date
     * @return string
     */
    public static function formatDmyHi($date)
    {
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        $hour = substr($date, 11, 2);
        $minute = substr($date, 14, 2);

        if ($date === '0000-00-00 00:00:00' || $date === '' || $date === null) {
            return null;
        }
        return $day.' '.(self::$lang === self::EN ? self::$arr_month_en[$month*1] : self::$arr_month_id[$month*1]).' '.$year.' - '.$hour.':'.$minute;
    }

    /**
     * Format to d-M-Y H:i:s
     *
     * @param $date
     * @return string
     */
    public static function formatDmyHis($date)
    {
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        $hour = substr($date, 11, 2);
        $minute = substr($date, 14, 2);
        $second = substr($date, 17, 2);

        if ($date === '0000-00-00 00:00:00' || $date === '' || $date === null) {
            return null;
        }
        return $day.' '.(self::$lang === self::EN ? self::$arr_month_en[$month*1] : self::$arr_month_id[$month*1]).' '.$year.' - '.$hour.':'.$minute.':'.$second;
    }

    /**
     * Format to d-m-Y H:i
     *
     * @param $date
     * @param $separator
     * @return string
     */
    public static function toDmyHi($date, $separator)
    {
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        $hour = substr($date, 11, 2);
        $minute = substr($date, 14, 2);

        if ($date === '0000-00-00 00:00:00' || $date === '' || $date === null) {
            return null;
        }
        return $day.$separator.$month.$separator.$year.' '.$hour.':'.$minute;
    }

    /**
     * Format to Y-m-d H:i
     *
     * @param $date
     * @return string
     */
    public static function toYmdHi($date)
    {
        $day = substr($date, 0, 2);
        $month = substr($date, 3, 2);
        $year = substr($date, 6, 4);

        $hour = substr($date, 11, 2);
        $minute = substr($date, 14, 2);

        if ($date === '0000-00-00 00:00:00' || $date === '' || $date === null) {
            return null;
        }
        return $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute;
    }

    /**
     * Get Day
     *
     * @param $date
     * @return string
     */
    public static function day($date)
    {
        if ($date === '0000-00-00 00:00:00' || $date === '' || $date === null) {
            return null;
        }
        return substr($date, 8, 2);
    }

    /**
     * Get Month
     *
     * @param $date
     * @return string
     */
    public static function month($date)
    {
        if ($date === '0000-00-00 00:00:00' || $date === '' || $date === null) {
            return null;
        }
        return substr($date, 5, 2);
    }

    /**
     * Get Year
     *
     * @param $date
     * @return string
     */
    public static function year($date)
    {
        if($date === '0000-00-00 00:00:00' || $date === '' || $date === null){
            return null;
        }
        return substr($date, 0, 4);
    }

    /**
     * Get Monthname
     *
     * @param $date
     * @return mixed
     */
    public static function monthName($date)
    {
        if ($date === '0000-00-00 00:00:00' || $date === '' || $date === null) {
            return null;
        }
        $month = substr($date, 5, 2);

        return (self::$lang === self::EN ? self::$arr_month_en[$month*1] : self::$arr_month_id[$month*1]);
    }

}

<?php
/**
 * Benchmark Library
 *
 * Benchmark tools
 *
 */

namespace Elips\Libraries;

class Benchmark
{

    /**
     * @var int
     */
    private static $time;

    /**
     * Get Time in Microsecond
     *
     * @return float
     */
    public static function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    /**
     * Start Benchmark Time
     *
     * @param string $flagname
     */
    public static function start($flagname = 'std')
    {
        self::$time[$flagname] = self::microtime_float();
    }

    /**
     * Start Benchmark Time
     *
     * @param string $flagname
     */
    public static function startTime($flagname = 'std')
    {
        self::$time[$flagname] = self::microtime_float();
    }

    /**
     * Get Benchmark Time
     *
     * @param string $flagname
     * @param int $round
     * @return float|int
     */
    public static function getTime($flagname = 'std', $round = 4)
    {
        if (isset(self::$time[$flagname])) {
            return round(self::microtime_float() - self::$time[$flagname], $round);
        }
        return 0;
    }

    /**
     * Get Memory Usage
     *
     * @param bool $real_usage
     * @return int
     */
    public static function memoryUsage($real_usage = false)
    {
        return memory_get_usage($real_usage);
    }

}

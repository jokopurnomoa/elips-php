<?php
/**
 * Image library
 *
 *
 */

namespace Elips\Libraries;

class ImageLib
{

    private static $imageLibrary = 'gd2';
    private static $sourceImage = '';
    private static $newImage = '';
    private static $createThumb = false;
    private static $maintainRatio = true;
    private static $maintainSize = true;
    private static $width = 0;
    private static $height = 0;
    private static $quality = 100;

    /**
     * Set Config
     *
     * @param array $config
     */
    public static function setConfig($config = array())
    {
        if($config != null){
            foreach($config as $key => $value){
                self::$$key = $value;
            }
        }
    }

    /**
     * Reset Config
     */
    public static function resetConfig()
    {
        self::$imageLibrary = 'gd2';
        self::$sourceImage = '';
        self::$newImage = '';
        self::$createThumb = false;
        self::$maintainRatio = true;
        self::$maintainSize = true;
        self::$width = 0;
        self::$height = 0;
        self::$quality = 100;
    }

    /**
     * Resize Image
     */
    public static function resize()
    {
        self::$quality = (int)self::$quality;
        if (self::$quality > 100) {
            self::$quality = 100;
        } elseif(self::$quality < 0) {
            self::$quality = 0;
        }

        if (self::$sourceImage != '') {
            $sourceImagePath = str_replace('//', '/', __DIR__ . '/../../' . self::$sourceImage);
            $sourceNewImagePath = str_replace('//', '/', __DIR__ . '/../../' . self::$newImage);

            if (file_exists($sourceImagePath)) {
                if (self::$imageLibrary === 'gd2') {
                    self::resizeGD2($sourceImagePath, $sourceNewImagePath);
                } elseif(APP_ENV === 'development') {
                    error_dump('Image library not supported!');die();
                }
            } elseif (APP_ENV === 'development') {
                error_dump('File \''.self::$sourceImage.'\' not found!');
            }
        }
    }

    /**
     * Resize Image Using GD2 Library
     *
     * @param $sourceImagePath
     * @param $sourceNewImagePath
     */
    private static function resizeGD2($sourceImagePath, $sourceNewImagePath)
    {
        $ext = trim(pathinfo($sourceImagePath, PATHINFO_EXTENSION));

        list($width, $height) = getimagesize($sourceImagePath);

        $source = null;
        if ($ext === 'png') {
            $source = imagecreatefrompng($sourceImagePath);
        } elseif ($ext === 'jpg' || $ext === 'jpeg') {
            $source = imagecreatefromjpeg($sourceImagePath);
        } elseif ($ext === 'gif') {
            $source = imagecreatefromgif($sourceImagePath);
        } elseif ($ext === 'bmp') {
            $source = imagecreatefromwbmp($sourceImagePath);
        } elseif (APP_ENV === 'development') {
            error_dump('Image format not supported!');die();
        }

        if ($source != null) {
            $is_size_maintained = false;
            if (self::$maintainSize) {
                if (self::$width > $width && self::$height > $height) {
                    self::$width = $width;
                    self::$height = $height;
                    $is_size_maintained = true;
                }
            }

            if (!$is_size_maintained) {
                if (self::$maintainRatio) {
                    if ($width > $height) {
                        self::$height = self::$width / ($width / $height);
                    } elseif ($width < $height) {
                        self::$width = self::$height / ($height / $width);
                    } else {
                        self::$width = self::$height;
                    }
                }
            }

            $thumb = imagecreatetruecolor(self::$width, self::$height);
            imagecopyresized($thumb, $source, 0, 0, 0, 0, self::$width, self::$height, $width, $height);

            if ($ext === 'png') {
                self::$newImage != '' ? imagepng($thumb, $sourceNewImagePath, self::$quality) : imagepng($thumb, $sourceImagePath, self::$quality);
            } elseif ($ext === 'jpg' || $ext === 'jpeg') {
                self::$newImage != '' ? imagejpeg($thumb, $sourceNewImagePath, self::$quality) : imagejpeg($thumb, $sourceImagePath, self::$quality);
            } elseif($ext === 'gif') {
                self::$newImage != '' ? imagegif($thumb, $sourceNewImagePath, self::$quality) : imagegif($thumb, $sourceImagePath, self::$quality);
            } elseif($ext === 'bmp') {
                self::$newImage != '' ? imagewbmp($thumb, $sourceNewImagePath, self::$quality) : imagewbmp($thumb, $sourceImagePath, self::$quality);
            }

        }
    }

}

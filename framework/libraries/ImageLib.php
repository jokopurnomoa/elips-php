<?php
/**
 * Image library
 *
 *
 */

class ImageLib
{

    private static $image_library = 'gd2';
    private static $source_image = '';
    private static $new_image = '';
    private static $create_thumb = false;
    private static $maintain_ratio = true;
    private static $maintain_size = true;
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
        self::$image_library = 'gd2';
        self::$source_image = '';
        self::$new_image = '';
        self::$create_thumb = false;
        self::$maintain_ratio = true;
        self::$maintain_size = true;
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

        if (self::$source_image != '') {
            $source_image_path = str_replace('//', '/', __DIR__ . '/../../' . self::$source_image);
            $source_new_image_path = str_replace('//', '/', __DIR__ . '/../../' . self::$new_image);

            if (file_exists($source_image_path)) {
                if (self::$image_library === 'gd2') {
                    self::resizeGD2($source_image_path, $source_new_image_path);
                } elseif(APP_ENV === 'development') {
                    error_dump('Image library not supported!');die();
                }
            } elseif (APP_ENV === 'development') {
                error_dump('File \''.self::$source_image.'\' not found!');
            }
        }
    }

    /**
     * Resize Image Using GD2 Library
     *
     * @param $source_image_path
     * @param $source_new_image_path
     */
    private static function resizeGD2($source_image_path, $source_new_image_path)
    {
        $ext = trim(pathinfo($source_image_path, PATHINFO_EXTENSION));

        list($width, $height) = getimagesize($source_image_path);

        $source = null;
        if ($ext === 'png') {
            $source = imagecreatefrompng($source_image_path);
        } elseif ($ext === 'jpg' || $ext === 'jpeg') {
            $source = imagecreatefromjpeg($source_image_path);
        } elseif ($ext === 'gif') {
            $source = imagecreatefromgif($source_image_path);
        } elseif ($ext === 'bmp') {
            $source = imagecreatefromwbmp($source_image_path);
        } elseif (APP_ENV === 'development') {
            error_dump('Image format not supported!');die();
        }

        if ($source != null) {
            $is_size_maintained = false;
            if (self::$maintain_size) {
                if (self::$width > $width && self::$height > $height) {
                    self::$width = $width;
                    self::$height = $height;
                    $is_size_maintained = true;
                }
            }

            if (!$is_size_maintained) {
                if (self::$maintain_ratio) {
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
                self::$new_image != '' ? imagepng($thumb, $source_new_image_path, self::$quality) : imagepng($thumb, $source_image_path, self::$quality);
            } elseif ($ext === 'jpg' || $ext === 'jpeg') {
                self::$new_image != '' ? imagejpeg($thumb, $source_new_image_path, self::$quality) : imagejpeg($thumb, $source_image_path, self::$quality);
            } elseif($ext === 'gif') {
                self::$new_image != '' ? imagegif($thumb, $source_new_image_path, self::$quality) : imagegif($thumb, $source_image_path, self::$quality);
            } elseif($ext === 'bmp') {
                self::$new_image != '' ? imagewbmp($thumb, $source_new_image_path, self::$quality) : imagewbmp($thumb, $source_image_path, self::$quality);
            }

        }
    }

}

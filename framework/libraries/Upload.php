<?php
/**
 * Upload Library
 *
 * Upload files
 *
 */

namespace Elips\Libraries;

class Upload
{

    private static $uploadPath = '';
    private static $filename = '';
    private static $maxSize = 0;
    private static $overwrite = true;
    private static $allowedTypes = array('jpeg');
    private static $fileSize = '';
    private static $fileType = '';
    private static $isImage = false;
    private static $imageWidth = 0;
    private static $imageHeight = 0;
    private static $maxWidth = 0;
    private static $maxHeight = 0;
    private static $errorMessage = '';
    private static $dataUpload = '';

    /**
     * Set Config
     *
     * @param array $config
     */
    public static function setConfig($config = array())
    {
        if ($config != null) {
            foreach ($config as $key => $value) {
                if (isset(self::$$key)) {
                    self::$$key = $value;
                }
            }
        }
    }

    /**
     * Reset Config
     *
     */
    public static function resetConfig()
    {
        self::$uploadPath = '';
        self::$filename = '';
        self::$maxSize = 0;
        self::$overwrite = true;
        self::$allowedTypes = array('jpeg');
        self::$fileSize = '';
        self::$fileType = '';
        self::$isImage = false;
        self::$imageWidth = 0;
        self::$imageHeight = 0;
        self::$maxWidth = 0;
        self::$maxHeight = 0;
        self::$errorMessage = '';
        self::$dataUpload = '';
    }

    /**
     * Do Upload Process
     *
     * @param string $fieldname
     * @return bool
     */
    public static function doUpload($fieldname = 'userfile')
    {
        if (self::$filename === '') {
            self::$filename = $_FILES[$fieldname]['name'];
        }

        $file_extension = pathinfo($_FILES[$fieldname]['name'],PATHINFO_EXTENSION);
        $target_file = trim(str_replace('//', '/', __DIR__ . '/../../' . self::$uploadPath . '/' . self::$filename . '.' . $file_extension));

        self::$fileType = self::getFileType($_FILES[$fieldname]['type']);
        self::$fileSize = $_FILES[$fieldname]['size'];
        self::$isImage = (getimagesize($_FILES[$fieldname]['tmp_name']) != false ? true : false);

        if (self::$isImage) {
            $image_size = getimagesize($_FILES[$fieldname]['tmp_name']);
            self::$imageWidth = $image_size[0];
            self::$imageHeight = $image_size[1];
        }

        $upload_ok = true;

        if (self::$allowedTypes != null) {
            $is_allowed = false;
            foreach (self::$allowedTypes as $row) {
                if (trim($row) === self::$fileType) {
                    $is_allowed = true;break;
                }
            }

            if (!$is_allowed) {
                $upload_ok = false;
                self::$errorMessage = 'File is not allowed.';
            }
        } else {
            $upload_ok = false;
            self::$errorMessage = 'File is not allowed.';
        }

        if (self::$fileSize > self::$maxSize && self::$maxSize > 0) {
            $upload_ok = false;
            self::$errorMessage = 'File is too large.';
        }

        if (self::$imageWidth > 0 && self::$maxHeight > 0) {
            if (self::$imageWidth > self::$maxWidth || self::$imageHeight > self::$maxHeight) {
                $upload_ok = false;
                self::$errorMessage = 'Image size is too big.';
            }
        }

        if (!self::$overwrite) {
            if (file_exists($target_file)) {
                $upload_ok = false;
                self::$errorMessage = 'File already exists.';
            }
        }

        if ($upload_ok) {
            if (move_uploaded_file($_FILES[$fieldname]['tmp_name'], $target_file)) {
                return true;
            } else {
                self::$errorMessage = 'There was an error uploading your file.';
            }
        }

        self::$dataUpload = array(
            'filename' => self::$filename,
            'file_size' => self::$fileSize,
            'file_type' => self::$fileType,
            'is_image' => self::$isImage
        );

        return false;
    }

    /**
     * Get File Type
     *
     * @param $type
     * @return string
     */
    private static function getFileType($type)
    {
        if (file_exists(APP_PATH . 'config/mimes.php')) {
            $mimes = null;
            require APP_PATH . 'config/mimes.php';
            if ($mimes != null) {
                foreach ($mimes as $key => $value) {
                    if (is_array($value)) {
                        if ($value != null){
                            foreach ($value as $val) {
                                if (trim($val) === $type) {
                                    return trim($key);
                                }
                            }
                        }
                    } else {
                        if (trim($value) === $type) {
                            return trim($key);
                        }
                    }
                }
            }
        }
    }

    /**
     * Get Upload Data Info
     *
     * @return string
     */
    public static function getUploadData()
    {
        return self::$dataUpload;
    }

    /**
     * Get Upload Error Info
     *
     * @return string
     */
    public static function getError()
    {
        return self::$errorMessage;
    }

}

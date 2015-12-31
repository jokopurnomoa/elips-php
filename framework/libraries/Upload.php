<?php
/**
 * Upload Library
 *
 * Upload files
 *
 */

class Upload {

    private static $upload_path = '';
    private static $filename = '';
    private static $max_size = 0;
    private static $overwrite = true;
    private static $allowed_types = array('jpeg');
    private static $file_size = '';
    private static $file_type = '';
    private static $is_image = false;
    private static $image_width = 0;
    private static $image_height = 0;
    private static $max_width = 0;
    private static $max_height = 0;
    private static $error_message = '';
    private static $data_upload = '';

    /**
     * Set Config
     *
     * @param array $config
     */
    public static function setConfig($config = array()){
        if($config != null){
            foreach($config as $key => $value){
                if(isset(self::$$key)){
                    self::$$key = $value;
                }
            }
        }
    }

    /**
     * Reset Config
     *
     */
    public static function resetConfig(){
        self::$upload_path = '';
        self::$filename = '';
        self::$max_size = 0;
        self::$overwrite = true;
        self::$allowed_types = array('jpeg');
        self::$file_size = '';
        self::$file_type = '';
        self::$is_image = false;
        self::$image_width = 0;
        self::$image_height = 0;
        self::$max_width = 0;
        self::$max_height = 0;
        self::$error_message = '';
        self::$data_upload = '';
    }

    /**
     * Do Upload Process
     *
     * @param string $fieldname
     * @return bool
     */
    public static function doUpload($fieldname = 'userfile'){
        if(self::$filename === ''){
            self::$filename = $_FILES[$fieldname]['name'];
        }

        $file_extension = pathinfo($_FILES[$fieldname]['name'],PATHINFO_EXTENSION);
        $target_file = trim(str_replace('//', '/', __DIR__ . '/../../' . self::$upload_path . '/' . self::$filename . '.' . $file_extension));

        self::$file_type = self::getFileType($_FILES[$fieldname]['type']);
        self::$file_size = $_FILES[$fieldname]['size'];
        self::$is_image = (getimagesize($_FILES[$fieldname]['tmp_name']) != false ? true : false);

        if(self::$is_image){
            $image_size = getimagesize($_FILES[$fieldname]['tmp_name']);
            self::$image_width = $image_size[0];
            self::$image_height = $image_size[1];
        }

        $upload_ok = true;

        if(self::$allowed_types != null){
            $is_allowed = false;
            foreach(self::$allowed_types as $row){
                if(trim($row) === self::$file_type){
                    $is_allowed = true;break;
                }
            }

            if(!$is_allowed){
                $upload_ok = false;
                self::$error_message = 'File is not allowed.';
            }
        } else {
            $upload_ok = false;
            self::$error_message = 'File is not allowed.';
        }

        if(self::$file_size > self::$max_size && self::$max_size > 0){
            $upload_ok = false;
            self::$error_message = 'File is too large.';
        }

        if(self::$image_width > 0 && self::$max_height > 0){
            if(self::$image_width > self::$max_width || self::$image_height > self::$max_height){
                $upload_ok = false;
                self::$error_message = 'Image size is too big.';
            }
        }

        if(!self::$overwrite){
            if(file_exists($target_file)){
                $upload_ok = false;
                self::$error_message = 'File already exists.';
            }
        }

        if($upload_ok){
            if (move_uploaded_file($_FILES[$fieldname]['tmp_name'], $target_file)) {
                return true;
            } else {
                self::$error_message = 'There was an error uploading your file.';
            }
        }

        self::$data_upload = array(
            'filename' => self::$filename,
            'file_size' => self::$file_size,
            'file_type' => self::$file_type,
            'is_image' => self::$is_image
        );

        return false;
    }

    /**
     * Get File Type
     *
     * @param $type
     * @return string
     */
    private static function getFileType($type){
        if(file_exists(APP_PATH . 'config/mimes.php')){
            $mimes = null;
            require APP_PATH . 'config/mimes.php';
            if($mimes != null){
                foreach($mimes as $key => $value){
                    if(is_array($value)){
                        if($value != null){
                            foreach($value as $val){
                                if(trim($val) === $type){
                                    return trim($key);
                                }
                            }
                        }
                    } else {
                        if(trim($value) === $type){
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
    public static function getUploadData(){
        return self::$data_upload;
    }

    /**
     * Get Upload Error Info
     *
     * @return string
     */
    public static function getError(){
        return self::$error_message;
    }

}

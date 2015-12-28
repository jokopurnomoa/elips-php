<?php
/**
 * File helper
 *
 *
 */

/**
 * Read File
 *
 * @param $file
 * @return null|string
 */
function read_file($file){
    if(file_exists($file)){
        $handle = fopen($file, 'r');
        flock($handle, LOCK_SH);
        if(filesize($file) > 0){
            $string = fread($handle, filesize($file));
        }
        flock($handle, LOCK_UN);
        fclose($handle);
        return $string;
    }
    return null;
}

/**
 * Write File
 *
 * @param $file
 * @param $string
 * @param string $mode
 * @return bool
 */
function write_file($file, $string, $mode = 'w'){
    $handle = fopen($file, $mode);
    flock($handle, LOCK_EX);
    fwrite($handle, $string);
    flock($handle, LOCK_UN);
    return fclose($handle);
}

/**
 * Delete File
 *
 * @param $file
 * @return bool
 */
function delete_file($file){
    if(file_exists($file)){
        $dirname = dirname($file);
        if($dirname !== $file){
            return unlink($file);
        }
    }
    return false;
}

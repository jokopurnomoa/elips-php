<?php
/**
 * Blade
 *
 * Simple blade templating
 *
 */

class Blade {

    public static function render($view, $data = null, $buffered = false){
        if(file_exists(APP_PATH . 'views/' . $view . '.blade.php')){
            ob_start();
            if($data != null){
                foreach($data as $key => $val){
                    $$key = $val;
                }
            }
            require APP_PATH . 'views/' . $view . '.blade.php';
            $__buffer = ob_get_contents();
            @ob_end_clean();

            if($__buffer != ''){
                for($__i = 0; $__i < strlen($__buffer); $__i++) {
                    // variable
                    if (strpos($__buffer, '{{{') !== false && strpos($__buffer, '}}}') !== false) {
                        $__start_pos = strpos($__buffer, '{{{');
                        $__end_pos = strpos($__buffer, '}}}');
                        $__var = substr($__buffer, $__start_pos + 3, $__end_pos - $__start_pos - 3);
                        $__buffer = str_replace('{{{' . $__var . '}}}', '<?php ' . $__var . ';?>', $__buffer);

                    }
                    else {
                        break;
                    }
                    // end variable
                }

                for($__i = 0; $__i < strlen($__buffer); $__i++) {
                    // comment
                    if (strpos($__buffer, '{{--') !== false && strpos($__buffer, '--}}') !== false) {
                        $__start_pos = strpos($__buffer, '{{--');
                        $__end_pos = strpos($__buffer, '--}}');
                        $__var = substr($__buffer, $__start_pos + 4, $__end_pos - $__start_pos - 4);
                        $__buffer = str_replace('{{--' . $__var . '--}}', '<?php /* echo ' . $__var . '; */?>', $__buffer);

                    }
                    else {
                        break;
                    }
                    // end comment
                }

                for($__i = 0; $__i < strlen($__buffer); $__i++) {
                    // echo variable
                    if (strpos($__buffer, '{{') !== false && strpos($__buffer, '}}') !== false) {
                        $__start_pos = strpos($__buffer, '{{');
                        $__end_pos = strpos($__buffer, '}}');
                        $__var = substr($__buffer, $__start_pos + 2, $__end_pos - $__start_pos - 2);
                        $__buffer = str_replace('{{' . $__var . '}}', '<?php echo ' . $__var . ';?>', $__buffer);

                    }
                    else {
                        break;
                    }
                    // end echo variable
                }

                for($__i = 0; $__i < strlen($__buffer); $__i++){
                    // if
                    if(strpos($__buffer, '@if') !== false){
                        $__start_pos = strpos($__buffer, '@if');;
                        $__end_pos = strpos($__buffer, ')', $__start_pos);
                        $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos + 1);
                        $__buffer = str_replace($__var, '<?php ' . $__var . ':?>', $__buffer);
                        $__buffer = str_replace('@if', 'if', $__buffer);
                    }
                    elseif(strpos($__buffer, '@elseif') !== false){
                        $__start_pos = strpos($__buffer, '@elseif');;
                        $__end_pos = strpos($__buffer, ')', $__start_pos);
                        $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos + 1);
                        $__buffer = str_replace($__var, '<?php ' . $__var . ':?>', $__buffer);
                        $__buffer = str_replace('@elseif', 'elseif', $__buffer);
                    }
                    elseif(strpos($__buffer, '@else') !== false){
                        $__buffer = str_replace('@else', '<?php else: ?>', $__buffer);
                    }
                    elseif(strpos($__buffer, '@endif') !== false){
                        $__buffer = str_replace('@endif', '<?php endif; ?>', $__buffer);

                    }
                    else {
                        break;
                    }
                    // endif
                }

                for($__i = 0; $__i < strlen($__buffer); $__i++){
                    // foreach
                    if(strpos($__buffer, '@foreach') !== false){
                        $__start_pos = strpos($__buffer, '@foreach');;
                        $__end_pos = strpos($__buffer, ')', $__start_pos);
                        $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos + 1);
                        $__buffer = str_replace($__var, '<?php ' . $__var . ':?>', $__buffer);
                        $__buffer = str_replace('@foreach', 'foreach', $__buffer);
                    }
                    if(strpos($__buffer, '@endforeach') !== false){
                        $__buffer = str_replace('@endforeach', '<?php endforeach; ?>', $__buffer);

                    }
                    else {
                        break;
                    }
                    // endforeach
                }

                for($__i = 0; $__i < strlen($__buffer); $__i++){
                    // for
                    if(strpos($__buffer, '@for') !== false){
                        $__start_pos = strpos($__buffer, '@for');;
                        $__end_pos = strpos($__buffer, ')', $__start_pos);
                        $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos + 1);
                        $__buffer = str_replace($__var, '<?php ' . $__var . ':?>', $__buffer);
                        $__buffer = str_replace('@for', 'for', $__buffer);
                    }
                    elseif(strpos($__buffer, '@endfor') !== false){
                        $__buffer = str_replace('@endfor', '<?php endfor; ?>', $__buffer);

                    }
                    else {
                        break;
                    }
                    // endfor
                }

                for($__i = 0; $__i < strlen($__buffer); $__i++){
                    // while
                    if(strpos($__buffer, '@while') !== false){
                        $__start_pos = strpos($__buffer, '@while');;
                        $__end_pos = strpos($__buffer, ')', $__start_pos);
                        $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos + 1);
                        $__buffer = str_replace($__var, '<?php ' . $__var . ':?>', $__buffer);
                        $__buffer = str_replace('@while', 'while', $__buffer);
                    }
                    elseif(strpos($__buffer, '@endwhile') !== false){
                        $__buffer = str_replace('@endwhile', '<?php endwhile; ?>', $__buffer);

                    }
                    else {
                        break;
                    }
                    // endwhile
                }
            }

            //echo $__buffer;die();
            ob_start();
            eval('?>' . $__buffer);
            $__buffer = ob_get_contents();
            @ob_end_clean();

            if($buffered){
                return $__buffer;
            } else {
                echo $__buffer;
            }
        } else {
            errorDump('Blade : File \'' . APP_PATH . 'views/' . $view . '.blade.php\' not found!');die();
        }
        return null;
    }

}

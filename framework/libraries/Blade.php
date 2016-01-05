<?php
/**
 * Blade Templating Library
 *
 * Simple blade templating
 *
 */

class Blade {

    /**
     * Render View
     *
     * @param $view
     * @param null $data
     * @param bool $buffered
     * @return mixed|null|string
     */
    public static function render($view, $data = null, $buffered = false){
        global $__module_path;
        $module_path = $__module_path;
        $module_view = '';
        $paths = explode('/', $view);

        if(strpos($view, '/') !== false){
            $module_path = str_replace('//', '/', 'modules/' . $paths[0] . '/');
            $module_view = trim(str_replace($paths[0] . '/', '', $view), '/');
        }

        $view = str_replace('.', '/', $view);
        if(file_exists(APP_PATH . 'views/' . $view . '.blade.php') && $module_view === ''){
            $__buffer = self::parse(read_file(APP_PATH . 'views/' . $view . '.blade.php'));
            $__buffer = self::run($__buffer, $data);

            if($buffered){
                return $__buffer;
            } else {
                echo $__buffer;
            }
        }
        elseif(file_exists(APP_PATH . $module_path . 'views/' . $module_view . '.blade.php')){
            $__buffer = self::parse(read_file(APP_PATH . $module_path . 'views/' . $module_view . '.blade.php'));
            $__buffer = self::run($__buffer, $data);
            if($buffered){
                return $__buffer;
            } else {
                echo $__buffer;
            }
        }
        elseif(APP_ENV === 'development') {
            error_dump('Blade : File \'' . APP_PATH . 'views/' . $view . '.blade.php\' not found!');
            die();
        }
        return null;
    }

    private static function run($__buffer, $data){
        ob_start();
        if($data !== null){
            foreach($data as $key => $val){
                $$key = $val;
            }
        }

        eval('?>' . $__buffer);
        $__buffer = ob_get_contents();
        @ob_end_clean();
        return $__buffer;
    }

    private static function str_replace_first($search, $replace, $subject) {
        $pos = strpos($subject, $search);
        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }
        return $subject;
    }

    private static function parse($__buffer){
        if($__buffer !== ''){

            // extend template
            $parent_view = null;
            $max_extend = substr_count($__buffer, '@extends');
            for($__i = 0; $__i < $max_extend; $__i++) {
                if(strpos($__buffer, '@extends') !== false){
                    $__start_pos = strpos($__buffer, '@extends');
                    $__end_pos = strpos($__buffer, ')', $__start_pos);
                    $__view = str_replace('.', '/', str_replace('\'', '', substr($__buffer, $__start_pos + 9, $__end_pos - $__start_pos  - 9)));
                    $__extend = substr($__buffer, $__start_pos, $__end_pos - $__start_pos + 2);
                    $__buffer = self::str_replace_first($__extend, '', $__buffer);

                    ob_start();
                    require(APP_PATH . 'views/' . $__view . '.blade.php');
                    $__view_buffer = ob_get_contents();
                    @ob_end_clean();

                    $parent_view = $__view_buffer;
                }
                else {
                    break;
                }
            }
            // end extend template


            // parse section
            $parse_result = self::parseSection($__buffer);
            $__buffer = $parse_result[0];
            $sections = $parse_result[1];

            if($parent_view != null && $sections != null){
                $parse_result = self::parseSection($parent_view);
                $parent_sections = $parse_result[1];

                foreach($sections as $key => $val){
                    if(isset($parent_sections[$key])){
                        $val = self::str_replace_first('@parent', $parent_sections[$key], $val);
                        $parent_view = self::str_replace_first('@section(\'' . $key . '\')' . $parent_sections[$key] . '@stop', $val, $parent_view);
                    } else {
                        $parent_view = self::str_replace_first('@yield(\'' . $key . '\')', $val, $parent_view);
                    }
                }

                $__buffer = $parent_view;
            }
            // end parse section

            // include template
            $max_loop = strlen($__buffer);
            for($__i = 0; $__i < $max_loop; $__i++) {
                if(strpos($__buffer, '@include(') !== false){
                    $__start_pos = strpos($__buffer, '@include(');
                    $__end_pos = strpos($__buffer, ')', $__start_pos);
                    $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos + 1);
                    $__view = str_replace('.', '/', str_replace('\'', '', substr($__buffer, $__start_pos + 9, $__end_pos - $__start_pos  - 9)));

                    ob_start();
                    require(APP_PATH . 'views/' . $__view . '.blade.php');
                    $__view_buffer = ob_get_contents();
                    @ob_end_clean();

                    $__buffer = self::str_replace_first($__var, $__view_buffer, $__buffer);
                }
                else {
                    break;
                }
            }
            // end include template

            // echo escaped variable
            for($__i = 0; $__i < $max_loop; $__i++) {
                if (strpos($__buffer, '{{{') !== false && strpos($__buffer, '}}}') !== false) {
                    $__start_pos = strpos($__buffer, '{{{');
                    $__end_pos = strpos($__buffer, '}}}');
                    $__var = substr($__buffer, $__start_pos + 3, $__end_pos - $__start_pos - 3);
                    $__buffer = self::str_replace_first('{{{' . $__var . '}}}', '<?php echo ' . htmlspecialchars(trim($__var)) . ';?>', $__buffer);

                }
                else {
                    break;
                }
            }
            // end echo escaped variable

            // comment
            for($__i = 0; $__i < $max_loop; $__i++) {
                if (strpos($__buffer, '{{--') !== false && strpos($__buffer, '--}}') !== false) {
                    $__start_pos = strpos($__buffer, '{{--');
                    $__end_pos = strpos($__buffer, '--}}');
                    $__var = substr($__buffer, $__start_pos + 4, $__end_pos - $__start_pos - 4);
                    $__buffer = self::str_replace_first('{{--' . $__var . '--}}', '<?php /* echo ' . trim($__var) . '; */?>', $__buffer);

                }
                else {
                    break;
                }
            }
            // end comment

            // echo variable
            for($__i = 0; $__i < $max_loop; $__i++) {
                if (strpos($__buffer, '{{') !== false && strpos($__buffer, '}}') !== false) {
                    $__start_pos = strpos($__buffer, '{{');
                    $__end_pos = strpos($__buffer, '}}');
                    $__var = substr($__buffer, $__start_pos + 2, $__end_pos - $__start_pos - 2);
                    $__buffer = self::str_replace_first('{{' . $__var . '}}', '<?php echo ' . trim($__var) . ';?>', $__buffer);

                }
                else {
                    break;
                }
            }
            // end echo variable
            // if
            for($__i = 0; $__i < $max_loop; $__i++){
                if(strpos($__buffer, '@if') !== false){
                    $__start_pos = strpos($__buffer, '@if');
                    $__end_pos = strpos($__buffer, PHP_EOL, $__start_pos);
                    $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos);

                    $__buffer = self::str_replace_first($__var, '<?php ' . $__var . ':?>', $__buffer);
                    $__buffer = self::str_replace_first('@if', 'if', $__buffer);
                }
                elseif(strpos($__buffer, '@elseif') !== false){
                    $__start_pos = strpos($__buffer, '@elseif');
                    $__end_pos = strpos($__buffer, PHP_EOL, $__start_pos);
                    $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos);
                    $__buffer = self::str_replace_first($__var, '<?php ' . $__var . ':?>', $__buffer);
                    $__buffer = self::str_replace_first('@elseif', 'elseif', $__buffer);
                }
                elseif(strpos($__buffer, '@else') !== false){
                    $__buffer = self::str_replace_first('@else', '<?php else: ?>', $__buffer);
                }
                elseif(strpos($__buffer, '@endif') !== false){
                    $__buffer = self::str_replace_first('@endif', '<?php endif; ?>', $__buffer);

                }
                else {
                    break;
                }
            }
            // endif

            // foreach
            for($__i = 0; $__i < $max_loop; $__i++){
                if(strpos($__buffer, '@foreach') !== false){
                    $__start_pos = strpos($__buffer, '@foreach');
                    $__end_pos = strpos($__buffer, ')', $__start_pos);
                    $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos + 1);
                    $__buffer = self::str_replace_first($__var, '<?php ' . $__var . ':?>', $__buffer);
                    $__buffer = self::str_replace_first('@foreach', 'foreach', $__buffer);
                }
                if(strpos($__buffer, '@endforeach') !== false){
                    $__buffer = self::str_replace_first('@endforeach', '<?php endforeach; ?>', $__buffer);

                }
                else {
                    break;
                }
            }
            // endforeach

            // for
            for($__i = 0; $__i < $max_loop; $__i++){
                if(strpos($__buffer, '@for') !== false){
                    $__start_pos = strpos($__buffer, '@for');
                    $__end_pos = strpos($__buffer, PHP_EOL, $__start_pos);
                    $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos);
                    $__buffer = self::str_replace_first($__var, '<?php ' . $__var . ':?>', $__buffer);
                    $__buffer = self::str_replace_first('@for', 'for', $__buffer);
                }
                elseif(strpos($__buffer, '@endfor') !== false){
                    $__buffer = self::str_replace_first('@endfor', '<?php endfor; ?>', $__buffer);

                }
                else {
                    break;
                }
            }
            // endfor

            // while
            for($__i = 0; $__i < $max_loop; $__i++){
                if(strpos($__buffer, '@while') !== false){
                    $__start_pos = strpos($__buffer, '@while');
                    $__end_pos = strpos($__buffer, PHP_EOL, $__start_pos);
                    $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos);
                    $__buffer = self::str_replace_first($__var, '<?php ' . $__var . ':?>', $__buffer);
                    $__buffer = self::str_replace_first('@while', 'while', $__buffer);
                }
                elseif(strpos($__buffer, '@endwhile') !== false){
                    $__buffer = self::str_replace_first('@endwhile', '<?php endwhile; ?>', $__buffer);

                }
                else {
                    break;
                }
            }
            // endwhile
        }

        return $__buffer;
    }

    private static function parseSection($__buffer){
        $sections = array();
        $max_section = substr_count($__buffer, '@section');
        for($__i = 0; $__i < $max_section; $__i++) {
            if(strpos($__buffer, '@section') !== false){
                $__start_pos = strpos($__buffer, '@section');
                $__end_pos_sec_name = strpos($__buffer, ')', $__start_pos);
                $__end_pos = strpos($__buffer, '@stop', $__start_pos);
                $__section_name = substr($__buffer, $__start_pos, $__end_pos_sec_name - $__start_pos + 1);

                $__section = substr($__buffer, $__start_pos + strlen($__section_name), $__end_pos - $__start_pos  - strlen($__section_name));
                $__buffer = self::str_replace_first($__section_name . $__section . '@stop', '', $__buffer);
                $__section_name = trim(rtrim(ltrim(trim(substr($__section_name, 8, strlen($__section_name))), '('), ')'), '\'');

                $sections[$__section_name] = $__section;
            }
            else {
                break;
            }
        }
        return array($__buffer, $sections);
    }

}

/**
 * View Library
 *
 * Basic load view
 *
 */

class View {

    /**
     * Render View (Loader::loadView alias)
     *
     * @param $view
     * @param null $data
     * @param bool $buffered
     * @return null|string
     */
    public static function render($view, $data = null, $buffered = false){
        Loader::loadView($view, $data, $buffered);
    }

}

<?php
/**
 * Blade Templating Library
 *
 * Simple blade templating
 *
 */

class Blade {

    /**
     * Experimental
     *
     * @var array
     */
    protected static $parseData = array();
    const HTML_SPECIALCHARS = 'SPECIALCHARS';
    const HTML_ENTITIES = 'ENTITIES';

    /**
     * Render View
     *
     * @param $view
     * @param null $data
     * @param bool $buffered
     * @return mixed|null|string
     */
    public static function render($view, $data = null, $buffered = false){
        $module_path = MODULE_PATH;
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

    /**
     * Running blade
     *
     * @param string  $__buffer
     * @param array   $data
     * @return string
     */
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

    /**
     * Replace first occurence of string
     *
     * @param string  $search
     * @param string  $replace
     * @param string  $subject
     * @return string
     */
    private static function str_replace_first($search, $replace, $subject) {
        $pos = strpos($subject, $search);
        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }
        return $subject;
    }

    /**
     * Parsing template
     *
     * @param string $__buffer
     * @return null|string
     */
    private static function parse($__buffer){
        if($__buffer !== ''){

            /**
             * Parse extends
             */
            $parent_view = self::parseExtends($__buffer);

            /* Parse section */
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
            /* End parse section */

            /**
             * Parse include
             */
            $__buffer = self::parseInclude($__buffer);

            /**
             * Parse echo with escaped variable
             */
            $__buffer = self::parseEchoWithEscapedVariable($__buffer);

            /**
             * Parse comment
             */
            $__buffer = self::parseComment($__buffer);

            /**
             * Parse echo variable without htmlentities
             */
            $__buffer = self::parseEchoWithoutHtmlEntities($__buffer);

            /**
             * Parse echo variable with htmlentities
             */
            $__buffer = self::parseEchoWithHtmlEntities($__buffer);

            /**
             * Parse if
             */
            $__buffer = self::parseIf($__buffer);

            /**
             * Parse foreach
             */
            $__buffer = self::parseForeach($__buffer);

            /**
             * Parse for
             */
            $__buffer = self::parseFor($__buffer);

            /**
             * Parse while
             */
            $__buffer = self::parseWhile($__buffer);

        }

        return $__buffer;
    }

    /**
     * Parse extends
     *
     * @param string $__buffer
     * @return null|string
     */
    private static function parseExtends($__buffer){
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
        return $parent_view;
    }

    /**
     * Parse include
     *
     * @param string $__buffer
     * @return string
     */
    private static function parseInclude($__buffer){
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

        return $__buffer;
    }

    /**
     * Parse echo with escaped variable
     *
     * @param string $__buffer
     * @return string
     */
    private static function parseEchoWithEscapedVariable($__buffer){
        $max_loop = strlen($__buffer);
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

        return $__buffer;
    }

    /**
     * Parse comment
     *
     * @param string $__buffer
     * @return string
     */
    private static function parseComment($__buffer){
        $max_loop = strlen($__buffer);
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

        return $__buffer;
    }

    /**
     * Parse echo without htmlentities
     *
     * @param string $__buffer
     * @return string
     */
    private static function parseEchoWithoutHtmlEntities($__buffer){
        $max_loop = strlen($__buffer);
        for($__i = 0; $__i < $max_loop; $__i++) {
            if (strpos($__buffer, '{!!') !== false && strpos($__buffer, '!!}') !== false) {
                $__start_pos = strpos($__buffer, '{!!');
                $__end_pos = strpos($__buffer, '!!}');
                $__var = substr($__buffer, $__start_pos + 3, $__end_pos - $__start_pos - 3);
                $__buffer = self::str_replace_first('{!!' . $__var . '!!}', '<?php echo ' . trim($__var) . ';?>', $__buffer);

            }
            else {
                break;
            }
        }

        return $__buffer;
    }

    /**
     * Parse echo with htmlentities
     *
     * @param string $__buffer
     * @return string
     */
    private static function parseEchoWithHtmlEntities($__buffer){
        $max_loop = strlen($__buffer);
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

        return $__buffer;
    }

    /**
     * Parse if
     *
     * @param string $__buffer
     * @return string
     */
    private static function parseIf($__buffer){
        $max_loop = strlen($__buffer);

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

        return $__buffer;
    }

    /**
     * Parse foreach
     *
     * @param string $__buffer
     * @return string
     */
    private static function parseForeach($__buffer){
        $max_loop = strlen($__buffer);

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

        return $__buffer;
    }

    /**
     * Parse for
     *
     * @param string $__buffer
     * @return string
     */
    private static function parseFor($__buffer){
        $max_loop = strlen($__buffer);

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

        return $__buffer;
    }

    /**
     * Parse while
     *
     * @param string $__buffer
     * @return string
     */
    private static function parseWhile($__buffer){
        $max_loop = strlen($__buffer);
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

        return $__buffer;
    }

    /**
     * Parsing template section
     *
     * @param string $__buffer
     * @return array
     */
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

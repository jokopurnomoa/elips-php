<?php
/**
 * Blade Templating Library
 *
 * Simple blade templating
 *
 */

class Blade
{

    /**
     * Experimental
     *
     * @var array
     */
    private static $rawData = array();

    private static $extendsTag = '@extends';
    private static $includeTag = '@include';
    private static $parentTag = '@parent';
    private static $sectionTag = '@section';
    private static $stopTag = '@stop';
    private static $yieldTag = '@yield';
    private static $echoEscapedStartTag = '{{{';
    private static $echoEscapedEndTag = '}}}';
    private static $commentStartTag = '{{--';
    private static $commentEndTag = '--}}';
    private static $echoStartTag = '{{';
    private static $echoEndTag = '}}';
    private static $rawTextStartTag = '@{{';
    private static $rawTextEndTag = '}}';
    private static $echoWithoutHtmlEntitiesStartTag = '{!!';
    private static $echoWithoutHtmlEntitiesEndTag = '!!}';
    private static $ifTag = '@if';
    private static $elseifTag = '@elseif';
    private static $elseTag = '@else';
    private static $endifTag = '@endif';
    private static $foreachTag = '@foreach';
    private static $endForeachTag = '@endforeach';
    private static $forTag = '@for';
    private static $endForTag = '@endfor';
    private static $whileTag = '@while';
    private static $endWhileTag = '@endwhile';

        /**
     * Render View
     *
     * @param $view
     * @param null $data
     * @param bool $buffered
     * @return mixed|null|string
     */
    public static function render($view, $data = null, $buffered = false)
    {
        global $modulePath;

        $inSelfModule = false;
        if(strpos($view, '/') === false){
            $inSelfModule = true;
        }

        $module_view = '';
        $paths = explode('/', $view);

        if (strpos($view, '/') !== false) {
            $modulePath = str_replace('//', '/', 'modules/' . $paths[0] . '/');
            $module_view = trim(str_replace($paths[0] . '/', '', $view), '/');
        }

        $view = str_replace('.', '/', $view);
        if (file_exists(APP_PATH . 'views/' . $view . '.blade.php') && $module_view === '' && !$inSelfModule) {
            $__buffer = self::parse(read_file(APP_PATH . 'views/' . $view . '.blade.php'));
            $__buffer = self::run($__buffer, $data);

            if ($buffered) {
                return $__buffer;
            } else {
                echo $__buffer;
            }
        }
        elseif (file_exists(APP_PATH . $modulePath . 'views/' . $view . '.blade.php')) {
            $__buffer = self::parse(read_file(APP_PATH . $modulePath . 'views/' . $view . '.blade.php'));
            $__buffer = self::run($__buffer, $data);
            if ($buffered) {
                return $__buffer;
            } else {
                echo $__buffer;
            }
        }
        elseif (APP_ENV === 'development') {
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
    private static function run($__buffer, $data)
    {
        ob_start();
        if ($data !== null) {
            foreach ($data as $key => $val) {
                $$key = $val;
            }
        }

        eval('?>' . $__buffer);
        $__buffer = ob_get_contents();
        @ob_end_clean();

        /* Display raw text */
        if (self::$rawData != null) {
            foreach (self::$rawData as $key => $val) {
                $__buffer = str_replace($key, $val, $__buffer);
            }
        }

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
    private static function str_replace_first($search, $replace, $subject)
    {
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
    private static function parse($__buffer)
    {
        if ($__buffer !== '') {

            /**
             * Parse echo raw text
             */
            $__buffer = self::parseEchoRawText($__buffer);

            /**
             * Parse extends
             */
            $parent_view = self::parseExtends($__buffer);

            /* Parse section */
            $parse_result = self::parseSection($__buffer);
            $__buffer = $parse_result[0];
            $sections = $parse_result[1];

            if ($parent_view != null && $sections != null) {
                $parse_result = self::parseSection($parent_view);
                $parent_sections = $parse_result[1];

                foreach ($sections as $key => $val) {
                    if (isset($parent_sections[$key])) {
                        $val = self::str_replace_first(self::$parentTag, $parent_sections[$key], $val);
                        $parent_view = self::str_replace_first(self::$sectionTag . '(\'' . $key . '\')' . $parent_sections[$key] . self::$stopTag, $val, $parent_view);
                    } else {
                        $parent_view = self::str_replace_first(self::$yieldTag . '(\'' . $key . '\')', $val, $parent_view);
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
    private static function parseExtends($__buffer)
    {
        $parent_view = null;
        $max_extend = substr_count($__buffer, self::$extendsTag);
        for ($__i = 0; $__i < $max_extend; $__i++) {
            if (strpos($__buffer, self::$extendsTag) !== false) {
                $__start_pos = strpos($__buffer, self::$extendsTag);
                $__end_pos = strpos($__buffer, ')', $__start_pos);
                $__view = str_replace('.', '/', str_replace('\'', '', substr($__buffer, $__start_pos + strlen(self::$extendsTag) + 1, $__end_pos - $__start_pos  - (strlen(self::$extendsTag) + 1))));
                $__extend = substr($__buffer, $__start_pos, $__end_pos - $__start_pos + 2);
                $__buffer = self::str_replace_first($__extend, '', $__buffer);

                ob_start();
                require(APP_PATH . 'views/' . $__view . '.blade.php');
                $__view_buffer = ob_get_contents();
                @ob_end_clean();

                $parent_view = $__view_buffer;
            } else {
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
    private static function parseInclude($__buffer)
    {
        $max_loop = strlen($__buffer);
        for ($__i = 0; $__i < $max_loop; $__i++) {
            if (strpos($__buffer, self::$includeTag . '(') !== false) {
                $__start_pos = strpos($__buffer, self::$includeTag . '(');
                $__end_pos = strpos($__buffer, ')', $__start_pos);
                $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos + 1);
                $__view = str_replace('.', '/', str_replace('\'', '', substr($__buffer, $__start_pos + strlen(self::$includeTag) + 1, $__end_pos - $__start_pos  - (strlen(self::$includeTag) + 1))));

                ob_start();
                require(APP_PATH . 'views/' . $__view . '.blade.php');
                $__view_buffer = ob_get_contents();
                @ob_end_clean();

                $__buffer = self::str_replace_first($__var, $__view_buffer, $__buffer);
            } else {
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
    private static function parseEchoWithEscapedVariable($__buffer)
    {
        $max_loop = strlen($__buffer);
        for ($__i = 0; $__i < $max_loop; $__i++) {
            if (strpos($__buffer, self::$echoEscapedStartTag) !== false && strpos($__buffer, self::$echoEscapedEndTag) !== false) {
                $__start_pos = strpos($__buffer, self::$echoEscapedStartTag);
                $__end_pos = strpos($__buffer, self::$echoEscapedEndTag);
                $__var = substr($__buffer, $__start_pos + strlen(self::$echoEscapedStartTag), $__end_pos - $__start_pos - strlen(self::$echoEscapedEndTag));
                $__buffer = self::str_replace_first(self::$echoEscapedStartTag . $__var . self::$echoEscapedEndTag, '<?php echo htmlspecialchars(' . trim($__var) . ');?>', $__buffer);
            } else {
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
    private static function parseComment($__buffer)
    {
        $max_loop = strlen($__buffer);
        for ($__i = 0; $__i < $max_loop; $__i++) {
            if (strpos($__buffer, self::$commentStartTag) !== false && strpos($__buffer, self::$commentEndTag) !== false) {
                $__start_pos = strpos($__buffer, self::$commentStartTag);
                $__end_pos = strpos($__buffer, self::$commentEndTag);
                $__var = substr($__buffer, $__start_pos + strlen(self::$commentStartTag), $__end_pos - $__start_pos - strlen(self::$commentEndTag));
                $__buffer = self::str_replace_first(self::$commentStartTag . $__var . self::$commentEndTag, '<?php /* echo ' . trim($__var) . '; */?>', $__buffer);
            } else {
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
    private static function parseEchoWithoutHtmlEntities($__buffer)
    {
        $max_loop = strlen($__buffer);
        for ($__i = 0; $__i < $max_loop; $__i++) {
            if (strpos($__buffer, self::$echoWithoutHtmlEntitiesStartTag) !== false && strpos($__buffer, self::$echoWithoutHtmlEntitiesEndTag) !== false) {
                $__start_pos = strpos($__buffer, self::$echoWithoutHtmlEntitiesStartTag);
                $__end_pos = strpos($__buffer, self::$echoWithoutHtmlEntitiesEndTag);
                $__var = substr($__buffer, $__start_pos + strlen(self::$echoWithoutHtmlEntitiesStartTag), $__end_pos - $__start_pos - strlen(self::$echoWithoutHtmlEntitiesEndTag));
                $__buffer = self::str_replace_first(self::$echoWithoutHtmlEntitiesStartTag . $__var . self::$echoWithoutHtmlEntitiesEndTag, '<?php echo ' . trim($__var) . ';?>', $__buffer);
            } else {
                break;
            }
        }

        return $__buffer;
    }

    /**
     * Parse raw text
     *
     * @param string $__buffer
     * @return string
     */
    private static function parseEchoRawText($__buffer)
    {
        $max_loop = strlen($__buffer);
        for ($__i = 0; $__i < $max_loop; $__i++) {
            if (strpos($__buffer, self::$rawTextStartTag) !== false && strpos($__buffer, self::$rawTextEndTag) !== false) {
                $__start_pos = strpos($__buffer, ' ' . self::$rawTextStartTag);
                $__end_pos = strpos($__buffer, ' ' . self::$rawTextEndTag);
                $__var = substr($__buffer, $__start_pos + strlen(self::$rawTextStartTag) + 1, $__end_pos - $__start_pos - strlen(self::$rawTextEndTag) - 1);
                $raw = 'RAW__' . sha1($__var);
                self::$rawData[$raw] = trim($__var);
                $__buffer = self::str_replace_first(self::$rawTextStartTag . $__var . self::$rawTextEndTag, '<?php echo \'' . $raw . '\';?>', $__buffer);
            } else {
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
    private static function parseEchoWithHtmlEntities($__buffer)
    {
        $max_loop = strlen($__buffer);
        for ($__i = 0; $__i < $max_loop; $__i++) {
            if (strpos($__buffer, self::$echoStartTag) !== false && strpos($__buffer, self::$echoEndTag) !== false) {
                $__start_pos = strpos($__buffer, self::$echoStartTag);
                $__end_pos = strpos($__buffer, self::$echoEndTag);
                $__var = substr($__buffer, $__start_pos + strlen(self::$echoStartTag), $__end_pos - $__start_pos - strlen(self::$echoEndTag));
                $__buffer = self::str_replace_first(self::$echoStartTag . $__var . self::$echoEndTag, '<?php echo htmlentities(' . trim($__var) . ', ENT_QUOTES);?>', $__buffer);
            } else {
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
    private static function parseIf($__buffer)
    {
        $max_loop = strlen($__buffer);

        for ($__i = 0; $__i < $max_loop; $__i++) {
            if (strpos($__buffer, self::$ifTag) !== false) {
                $__start_pos = strpos($__buffer, self::$ifTag);
                $__end_pos = strpos($__buffer, PHP_EOL, $__start_pos);
                $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos);

                $__buffer = self::str_replace_first($__var, '<?php ' . $__var . ':?>', $__buffer);
                $__buffer = self::str_replace_first(self::$ifTag, 'if', $__buffer);
            }
            elseif(strpos($__buffer, self::$elseifTag) !== false) {
                $__start_pos = strpos($__buffer, self::$elseifTag);
                $__end_pos = strpos($__buffer, PHP_EOL, $__start_pos);
                $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos);
                $__buffer = self::str_replace_first($__var, '<?php ' . $__var . ':?>', $__buffer);
                $__buffer = self::str_replace_first(self::$elseifTag, 'elseif', $__buffer);
            }
            elseif(strpos($__buffer, self::$elseTag) !== false) {
                $__buffer = self::str_replace_first(self::$elseTag, '<?php else: ?>', $__buffer);
            }
            elseif(strpos($__buffer, self::$endifTag) !== false) {
                $__buffer = self::str_replace_first(self::$endifTag, '<?php endif; ?>', $__buffer);

            } else {
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
    private static function parseForeach($__buffer)
    {
        $max_loop = strlen($__buffer);

        for ($__i = 0; $__i < $max_loop; $__i++) {
            if (strpos($__buffer, self::$foreachTag) !== false) {
                $__start_pos = strpos($__buffer, self::$foreachTag);
                $__end_pos = strpos($__buffer, ')', $__start_pos);
                $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos + 1);
                $__buffer = self::str_replace_first($__var, '<?php ' . $__var . ':?>', $__buffer);
                $__buffer = self::str_replace_first(self::$foreachTag, 'foreach', $__buffer);
            }
            if (strpos($__buffer, self::$endForeachTag) !== false) {
                $__buffer = self::str_replace_first(self::$endForeachTag, '<?php endforeach; ?>', $__buffer);
            } else {
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
    private static function parseFor($__buffer)
    {
        $max_loop = strlen($__buffer);

        for ($__i = 0; $__i < $max_loop; $__i++) {
            if (strpos($__buffer, self::$forTag) !== false) {
                $__start_pos = strpos($__buffer, self::$forTag);
                $__end_pos = strpos($__buffer, PHP_EOL, $__start_pos);
                $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos);
                $__buffer = self::str_replace_first($__var, '<?php ' . $__var . ':?>', $__buffer);
                $__buffer = self::str_replace_first(self::$forTag, 'for', $__buffer);
            }
            elseif (strpos($__buffer, self::$endForTag) !== false) {
                $__buffer = self::str_replace_first(self::$endForTag, '<?php endfor; ?>', $__buffer);
            } else {
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
    private static function parseWhile($__buffer)
    {
        $max_loop = strlen($__buffer);
        for ($__i = 0; $__i < $max_loop; $__i++) {
            if (strpos($__buffer, self::$whileTag) !== false) {
                $__start_pos = strpos($__buffer, self::$whileTag);
                $__end_pos = strpos($__buffer, PHP_EOL, $__start_pos);
                $__var = substr($__buffer, $__start_pos, $__end_pos - $__start_pos);
                $__buffer = self::str_replace_first($__var, '<?php ' . $__var . ':?>', $__buffer);
                $__buffer = self::str_replace_first(self::$whileTag, 'while', $__buffer);
            }
            elseif (strpos($__buffer, self::$endWhileTag) !== false) {
                $__buffer = self::str_replace_first(self::$endWhileTag, '<?php endwhile; ?>', $__buffer);
            } else {
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
    private static function parseSection($__buffer)
    {
        $sections = array();
        $max_section = substr_count($__buffer, self::$sectionTag);
        for ($__i = 0; $__i < $max_section; $__i++) {
            if (strpos($__buffer, self::$sectionTag) !== false) {
                $__start_pos = strpos($__buffer, self::$sectionTag);
                $__end_pos_sec_name = strpos($__buffer, ')', $__start_pos);
                $__end_pos = strpos($__buffer, self::$stopTag, $__start_pos);
                $__section_name = substr($__buffer, $__start_pos, $__end_pos_sec_name - $__start_pos + 1);

                $__section = substr($__buffer, $__start_pos + strlen($__section_name), $__end_pos - $__start_pos  - strlen($__section_name));
                $__buffer = self::str_replace_first($__section_name . $__section . self::$stopTag, '', $__buffer);
                $__section_name = trim(rtrim(ltrim(trim(substr($__section_name, strlen(self::$sectionTag), strlen($__section_name))), '('), ')'), '\'');

                $sections[$__section_name] = $__section;
            } else {
                break;
            }
        }
        return array($__buffer, $sections);
    }

    /**
     * Set echo start tag
     *
     * @param string $tag
     */
    public static function setEchoStartTag($tag)
    {
        self::$echoStartTag = $tag;
    }

    /**
     * Set echo end tag
     *
     * @param string $tag
     */
    public static function setEchoEndTag($tag)
    {
        self::$echoEndTag = $tag;
    }

    /**
     * Set echo escaped start tag
     *
     * @param string $tag
     */
    public static function setEchoEscapedStartTag($tag)
    {
        self::$echoEscapedStartTag = $tag;
    }

    /**
     * Set echo escaped end tag
     *
     * @param string $tag
     */
    public static function setEchoEscapedEndTag($tag)
    {
        self::$echoEscapedEndTag = $tag;
    }

    /**
     * Set echo without html entities start tag
     *
     * @param string $tag
     */
    public static function setEchoWithoutHtmlEntitiesStartTag($tag)
    {
        self::$echoWithoutHtmlEntitiesStartTag = $tag;
    }

    /**
     * Set echo without html entities end tag
     *
     * @param string $tag
     */
    public static function setEchoWithoutHtmlEntitiesEndTag($tag)
    {
        self::$echoWithoutHtmlEntitiesEndTag = $tag;
    }

    /**
     * Set comment start tag
     *
     * @param string $tag
     */
    public static function setCommentStartTag($tag)
    {
        self::$commentStartTag = $tag;
    }

    /**
     * Set comment end tag
     *
     * @param string $tag
     */
    public static function setCommentEndTag($tag)
    {
        self::$commentEndTag = $tag;
    }

}

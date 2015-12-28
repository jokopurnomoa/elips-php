<?php
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

<?php
/**
 * View Library
 *
 * Basic load view
 *
 */

namespace Elips\Libraries;

use Elips\Core\Load;

class View
{

    /**
     * Render View (Load::view alias)
     *
     * @param string        $view
     * @param null|array    $data
     * @param bool          $buffered
     * @return null|string
     */
    public static function render($view, $data = null, $buffered = false)
    {
        Load::view($view, $data, $buffered);
    }

}

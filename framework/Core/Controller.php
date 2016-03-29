<?php
/**
 * Base Controller
 *
 *
 */

namespace Elips\Core;

class Controller
{

    protected $data;
    protected $instance;

    /**
     * Controller Constructor
     */
    public function __construct()
    {
        $this->instance = get_instance();
        $this->data = $this->instance->data;
    }

}

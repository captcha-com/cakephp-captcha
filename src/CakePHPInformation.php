<?php

namespace CakeCaptcha;

use Cake\Routing\Router;

final class CakePHPInformation
{
    /**
     * Disable instance creation.
     */
    private function __construct() {}

    /**
     * Get config folder path.
     *
     * @param string  $path
     * @return string
     */
    public static function getConfigPath($path = '')
    {
        return ROOT . DS . 'config' . ($path ? DS . $path : $path);
    }

    /**
     * Get controller folder path.
     *
     * @param string  $path
     * @return string
     */
    public static function getControllersPath($path = '')
    {
        return APP . 'Controller' . ($path ? DS . $path : $path);
    }

    /**
     * Get the base url of web application.
     *
     * @return string
     */
    public static function getBaseUrl()
    {
      	return Router::url('/', true);
    }
}

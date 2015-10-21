<?php namespace CakeCaptcha;

use Cake\Routing\Router;

final class CakePHPInformation {

    /**
     * Disable instance creation.
     */
    private function __construct() {}

    /**
     * Get config folder path.
     *
     * @return string
     */
    public static function GetConfigPath() {
        return ROOT . DS . 'config';
    }

    /**
     * Get controller folder path.
     *
     * @return string
     */
    public static function GetControllersPath() {
        return APP . 'Controller';
    }

    /**
     * Get the base url of web application.
     *
     * @return string
     */
    public static function GetBaseUrl() {
      	return Router::url('/', true);
    }

}

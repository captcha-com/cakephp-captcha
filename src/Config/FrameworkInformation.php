<?php namespace CakeCaptcha\Config;

use Cake\Routing\Router;

final class FrameworkInformation {

    // disable instance creation
  	private function __construct() {}


  	public static function GetControllersPath() {
  		return APP . 'Controller';
  	}


    public static function GetBaseUrl() {
      return Router::url('/', true);
    }
}

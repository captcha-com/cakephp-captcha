<?php

use Cake\Routing\Router;

// registering a route for the CaptchaHandler controller
Router::connect('/captcha_handler/index', array('plugin' => 'CakeCaptcha', 'controller' => 'CaptchaHandler', 'action' => 'index'));

// registering a route to the CaptchaResource controller
Router::connect('/captcha_resource/get/*', array('plugin' => 'CakeCaptcha', 'controller' => 'CaptchaResource', 'action' => 'GetResource'));

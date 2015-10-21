<?php

use Cake\Routing\Router;

// Register default route of the library
Router::connect('/captcha_handler/index', array('plugin' => 'CakeCaptcha', 'controller' => 'CaptchaHandler', 'action' => 'index'));
Router::connect('/captcha_resource/get/*', array('plugin' => 'CakeCaptcha', 'controller' => 'CaptchaResource', 'action' => 'GetResource'));

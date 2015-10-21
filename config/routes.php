<?php

use Cake\Routing\Router;

// Registering the default route of the BotDetect Library
Router::connect('/captcha_handler/index', array('plugin' => 'CakeCaptcha', 'controller' => 'CaptchaHandler', 'action' => 'index'));
Router::connect('/captcha_resource/get/*', array('plugin' => 'CakeCaptcha', 'controller' => 'CaptchaResource', 'action' => 'GetResource'));

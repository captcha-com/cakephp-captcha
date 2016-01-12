<?php

use Cake\Routing\Router;

// registering a route for the CaptchaHandler controller
Router::connect('/captcha-handler', array('plugin' => 'CakeCaptcha', 'controller' => 'CaptchaHandler', 'action' => 'index'));

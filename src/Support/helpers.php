<?php

use Cake\Routing\Router;
use CakeCaptcha\Controller\Component\CaptchaComponent;
use CakeCaptcha\Controller\Component\SimpleCaptchaComponent;

if (! function_exists('captcha_library_is_loaded')) {
    /**
     * Check Captcha library is loaded or not.
     *
     * @return bool
     */
    function captcha_library_is_loaded()
    {
        return class_exists('BDC_CaptchaBase');
    }
}

if (! function_exists('captcha_instance')) {
    /**
     * Get Captcha object instance.
     *
     * @return object
     */
    function &captcha_instance()
    {
        return CaptchaComponent::getInstance();
    }
}

if (! function_exists('simple_captcha_instance')) {
    /**
     * Get SimpleCaptcha object instance.
     *
     * @return object
     */
    function &simple_captcha_instance()
    {
        return SimpleCaptchaComponent::getInstance();
    }
}

if (! function_exists('captcha_image_html')) {
    /**
     * Generate Captcha image html.
     *
     * @return string
     */
    function captcha_image_html()
    {
        $captcha =& captcha_instance();
        return $captcha->Html();
    }
}

if (! function_exists('simple_captcha_image_html')) {
    /**
     * Generate Captcha image html.
     *
     * @return string
     */
    function simple_captcha_image_html()
    {
        $captcha =& simple_captcha_instance();
        return $captcha->Html();
    }
}

if (! function_exists('captcha_validate')) {
    /**
     * Validate user's captcha code.
     *
     * @param string  $userInput
     * @param string  $instanceId
     * @return bool
     */
    function captcha_validate($userInput = null, $instanceId = null)
    {
        $captcha =& captcha_instance();
        return $captcha->Validate($userInput, $instanceId);
    }
}

if (! function_exists('simple_captcha_validate')) {
    /**
     * Validate user's captcha code.
     *
     * @param string  $userInput
     * @param string  $instanceId
     * @return bool
     */
    function simple_captcha_validate($userInput = null, $captchaId = null)
    {
        $captcha =& simple_captcha_instance();
        return $captcha->Validate($userInput, $captchaId);
    }
}

if (! function_exists('captcha_is_solved')) {
    /**
     * Check Captcha is solved or not.
     *
     * @return bool
     */
    function captcha_is_solved()
    {
        $captcha =& captcha_instance();
        return $captcha->IsSolved;
    }
}

if (! function_exists('captcha_reset')) {
    /**
     * Reset captcha for current instance.
     *
     * @return void
     */
    function captcha_reset()
    {
        $captcha =& captcha_instance();
        return $captcha->Reset();
    }
}

if (! function_exists('captcha_layout_stylesheet_url')) {
    /**
     * Generate Captcha layout stylesheet url.
     *
     * @return string
     */
    function captcha_layout_stylesheet_url()
    {
        return Router::url('/', true) . 'captcha-handler?get=bdc-layout-stylesheet.css';
    }
}

<?php

namespace CakeCaptcha\Controller\Component;

use Cake\Controller\Component;
use CakeCaptcha\Controller\Exception\InvalidArgumentException;
use CakeCaptcha\Controller\Exception\UnexpectedTypeException;
use CakeCaptcha\Support\SimpleLibraryLoader;

class SimpleCaptchaComponent extends Component
{
    /**
     * @var object
     */
    private $captcha;

    /**
     * @var object
     */
    private static $instance;

    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize(array $params)
    {
        self::$instance =& $this;

        // load botdetect captcha library
        SimpleLibraryLoader::load();
        
        $captchaId = null;
        if(isset($params['captchaId'])) {
            $captchaId = $params['captchaId'];
        }

        // init botdetect captcha instance
        $this->initCaptcha($params, $captchaId);
    }

    /**
     * Initialize CAPTCHA object instance.
     *
     * @param  array  $config
     * @return void
     */
    public function initCaptcha(array $params, $captchaId = null)
    {
        if (array_key_exists('captchaStyleName', $params)) {
            $captchaStyleName = $params['captchaStyleName'];
        } else {
            $captchaStyleName = 'defaultCaptcha';
        }

        $this->captcha = new \SimpleCaptcha($captchaStyleName, $captchaId);
    }

    /**
     * Get SimpleCaptchaComponent object instance.
     *
     * @return object
     */
    public static function &getInstance()
    {
        return self::$instance;
    }

    public function __call($method, $args = array())
    {
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $args);
        }

        if (method_exists($this->captcha, $method)) {
            return call_user_func_array(array($this->captcha, $method), $args);
        }

        if (method_exists($this->captcha->get_CaptchaBase(), $method)) {
            return call_user_func_array(array($this->captcha->get_CaptchaBase(), $method), $args);
        }
    }

    /**
     * Auto-magic helpers for civilized property access.
     */
    public function __get($name)
    {
        if (method_exists($this->captcha->get_CaptchaBase(), ($method = 'get_'.$name))) {
            return $this->captcha->get_CaptchaBase()->$method();
        }

        if (method_exists($this->captcha, ($method = 'get_'.$name))) {
            return $this->captcha->$method();
        }

        if (method_exists($this, ($method = 'get_'.$name))) {
            return $this->$method();
        }
    }

    public function __isset($name)
    {
        if (method_exists($this->captcha->get_CaptchaBase(), ($method = 'isset_'.$name))) {
            return $this->captcha->get_CaptchaBase()->$method();
        }

        if (method_exists($this->captcha, ($method = 'isset_'.$name))) {
            return $this->captcha->$method();
        }

        if (method_exists($this, ($method = 'isset_'.$name))) {
            return $this->$method();
        }
    }

    public function __set($name, $value)
    {
        if (method_exists($this->captcha->get_CaptchaBase(), ($method = 'set_'.$name))) {
            return $this->captcha->get_CaptchaBase()->$method($value);
        }

        if (method_exists($this->captcha, ($method = 'set_'.$name))) {
            $this->captcha->$method($value);
        } else if (method_exists($this, ($method = 'set_'.$name))) {
            $this->$method($value);
        }
    }

    public function __unset($name)
    {
        if (method_exists($this->captcha->get_CaptchaBase(), ($method = 'unset_'.$name))) {
            return $this->captcha->get_CaptchaBase()->$method();
        }

        if (method_exists($this->captcha, ($method = 'unset_'.$name))) {
            $this->captcha->$method();
        } else if (method_exists($this, ($method = 'unset_'.$name))) {
            $this->$method();
        }
    }
}


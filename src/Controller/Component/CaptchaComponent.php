<?php

namespace CakeCaptcha\Controller\Component;

use Cake\Controller\Component;
use CakeCaptcha\Controller\Exception\InvalidArgumentException;
use CakeCaptcha\Controller\Exception\UnexpectedTypeException;
use CakeCaptcha\Support\LibraryLoader;
use CakeCaptcha\Support\UserCaptchaConfiguration;

class CaptchaComponent extends Component
{
    /**
     * @var object
     */
    private $captcha;
    
    /**
     * BotDetect CakePHP CAPTCHA plugin information.
     *
     * @var array
     */
    public static $productInfo;

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

        $session = $this->request->session();

        // load botdetect captcha library
        LibraryLoader::load($session);

        // change the keys in $param array to lowercase,
        // this will avoid user being able to pass in a lowercase option (e.g. captchaconfig)
        $params = array_change_key_case($params, CASE_LOWER);

        if (empty($params) ||
            !array_key_exists('captchaconfig', $params) ||
            empty($params['captchaconfig'])
        ) {
            $errorMessage  = 'The BotDetect Captcha component requires you to declare "captchaConfig" option and assigns a captcha configuration key defined in config/captcha.php file.<br>';
            $errorMessage .= 'For example: $this->loadComponent(\'CakeCaptcha.Captcha\', [\'captchaConfig\' => \'ContactCaptcha\']);';
            throw new InvalidArgumentException($errorMessage);
        }

        $captchaId = $params['captchaconfig'];

        // get captcha config
        $config = UserCaptchaConfiguration::get($captchaId);

        if (is_null($config)) {
            throw new InvalidArgumentException(sprintf('The "%s" option could not be found in config/captcha.php file.', $captchaId));
        }

        if (!is_array($config)) {
            throw new UnexpectedTypeException($config, 'array');
        }

        // init botdetect captcha instance
        $this->initCaptcha($config);

        // execute user's captcha configuration options
        UserCaptchaConfiguration::execute($this->captcha, $config);
    }

    /**
     * Initialize CAPTCHA object instance.
     *
     * @param  array  $config
     * @return void
     */
    public function initCaptcha(array $config)
    {
        // set captchaId and create an instance of Captcha
        $captchaId = (array_key_exists('CaptchaId', $config)) ? $config['CaptchaId'] : 'defaultCaptchaId';
        $this->captcha = new \Captcha($captchaId);

        // set user's input id
        if (array_key_exists('UserInputId', $config)) {
            $this->captcha->UserInputId = $config['UserInputId'];
        }
    }

    /**
     * Get CaptchaComponent object instance.
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
    }

    /**
     * Auto-magic helpers for civilized property access.
     */
    public function __get($name)
    {
        if (method_exists($this->captcha, ($method = 'get_'.$name))) {
            return $this->captcha->$method();
        }

        if (method_exists($this, ($method = 'get_'.$name))) {
            return $this->$method();
        }
    }

    public function __isset($name)
    {
        if (method_exists($this->captcha, ($method = 'isset_'.$name))) {
            return $this->captcha->$method();
        } 

        if (method_exists($this, ($method = 'isset_'.$name))) {
            return $this->$method();
        }
    }

    public function __set($name, $value)
    {
        if (method_exists($this->captcha, ($method = 'set_'.$name))) {
            $this->captcha->$method($value);
        } else if (method_exists($this, ($method = 'set_'.$name))) {
            $this->$method($value);
        }
    }

    public function __unset($name)
    {
        if (method_exists($this->captcha, ($method = 'unset_'.$name))) {
            $this->captcha->$method();
        } else if (method_exists($this, ($method = 'unset_'.$name))) {
            $this->$method();
        }
    }

    /**
     * Get the BotDetect CakePHP CAPTCHA plugin information.
     *
     * @return array
     */
    public static function getProductInfo()
    {
        return self::$productInfo;
    }
}

// static field initialization
CaptchaComponent::$productInfo = [
    'name' => 'BotDetect PHP Captcha integration for the CakePHP framework', 
    'version' => '4.0.0-Dev'
];

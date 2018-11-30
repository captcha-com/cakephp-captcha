<?php

namespace CakeCaptcha\Support;

final class Path
{
    /**
     * Disable instance creation.
     */
    private function __construct() {}

    /**
     * Get CakePHP's config folder path.
     *
     * @param string  $path
     * @return string
     */
    public static function getCakePHPConfigPath($path = '')
    {
        return ROOT . DS . 'config' . ($path ? DS . $path : $path);
    }

    /**
     * Get CakePHP's controller folder path.
     *
     * @param string  $path
     * @return string
     */
    public static function getCakePHPControllersPath($path = '')
    {
        return APP . 'Controller' . ($path ? DS . $path : $path);
    }

    /**
     * Physical path of the captcha-com/captcha package.
     *
     * @return string
     */
    public static function getCaptchaLibPath()
    {
        return __DIR__ . '/../../../captcha/';
    }

    /**
     * Physical path of public directory which is located inside the captcha-com/captcha package.
     *
     * @return string
     */
    public static function getPublicDirPathInLibrary()
    {
        return self::getCaptchaLibPath() . 'lib/botdetect/public/';
    }

    /**
     * Physical path of botdetect.php file.
     *
     * @return string
     */
    public static function getBotDetectFilePath()
    {
        return __DIR__ . '/../Provider/botdetect.php';
    }

    /**
     * Physical path of simple-botdetect.php file.
     *
     * @return string
     */
    public static function getSimpleBotDetectFilePath()
    {
        return __DIR__ . '/../Provider/simple-botdetect.php';
    }

    /**
     * Physical path of captcha config defaults file.
     *
     * @return string
     */
    public static function getCaptchaConfigDefaultsFilePath()
    {
        return __DIR__ . '/CaptchaConfigDefaults.php';
    }

    /**
     * Physical path of user captcha config file.
     *
     * @return string
     */
    public static function getUserCaptchaConfigFilePath()
    {
        return self::getCakePHPConfigPath('captcha.php');
    }
}

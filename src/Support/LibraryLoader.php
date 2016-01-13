<?php

namespace CakeCaptcha\Support;

use CakeCaptcha\Support\Path;

final class LibraryLoader
{
    /**
     * Disable instance creation.
     */
    private function __construct() {}

    /**
     * Load BotDetect CAPTCHA Library and override Captcha Library settings.
     *
     * @param object  $session
     * @return void
     */
    public static function load($session)
    {
        // load bd php library
        self::loadBotDetectLibrary();

        // load the captcha configuration defaults
        self::loadCaptchaConfigDefaults($session);
    }

    /**
     * Load BotDetect CAPTCHA Library.
     *
     * @return void
     */
    private static function loadBotDetectLibrary()
    {
        self::includeFile(Path::getBotDetectFilePath(), true);
    }

    /**
     * Load the captcha configuration defaults.
     *
     * @return void
     */
    private static function loadCaptchaConfigDefaults($session)
    {
        self::includeFile(Path::getCaptchaConfigDefaultsFilePath(), true, $session);
    }

    /**
     * Include a file.
     *
     * @param string  $filePath
     * @param bool  $once
     * @return void
     */
    private static function includeFile($filePath, $once = false, $includeData = null)
    {
        if (is_file($filePath)) {
            $once ? include_once($filePath) : include($filePath);
        }
    }
}

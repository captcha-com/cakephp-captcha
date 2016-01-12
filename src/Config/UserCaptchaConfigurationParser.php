<?php

namespace CakeCaptcha\Config;

class UserCaptchaConfigurationParser
{
    /**
     * Session variable to store user's captcha config.
     *
     * @const string
     */
    const BDC_USER_CAPTCHA_CONFIG = 'BDC_USER_CAPTCHA_CONFIG';

    /**
     * @var string
     */
    private $filePath;

    /**
     * Create a new User Captcha Configuration Parser object.
     *
     * @param string  $filePath
     * @return void
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Get User's captcha config.
     *
     * @return array
     */
    public function getConfigs()
    {
        return $this->configsIsModified() ? $this->createConfigs() : $this->getConfigsInSession();
    }

    /**
     * Check captcha config is modified or not.
     *
     * @return bool
     */
    private function configsIsModified()
    {
        if (!isset($_SESSION[self::BDC_USER_CAPTCHA_CONFIG])) {
            return true;
        }

        $configs = $this->maybeUnserialize($_SESSION[self::BDC_USER_CAPTCHA_CONFIG]);

        $oldLastModifiedTime = is_array($configs) ? $configs['_file_modification_time'] : 0;
        $lastModifiedTime = $this->getConfigFileModificationTime();

        return $lastModifiedTime !== $oldLastModifiedTime;
    }

    /**
     * Get modification time of config file.
     *
     * @param string  $filePath
     * @return int
     */
    private function getConfigFileModificationTime()
    {
        return filemtime($this->filePath);
    }

    /**
     * Get User's captcha config in the session data.
     *
     * @return array
     */
    private function getConfigsInSession()
    {
        return $this->maybeUnserialize($_SESSION[self::BDC_USER_CAPTCHA_CONFIG]);
    }

    /**
     * Create new captcha config from config file and store it in the session data.
     *
     * @return array
     */
    private function createConfigs()
    {
        $contents = $this->wrapClassExistsAroundMethods($this->getConfigContents());
        $configs = eval($contents);
        $configs['_file_modification_time'] = $this->getConfigFileModificationTime();
        $this->storeConfigsInSession($configs);
        return $configs;
    }

    /**
     * Store user's captcha config in the session data.
     *
     * @param array  $configs
     * @return void
     */
    private function storeConfigsInSession(array $configs)
    {
        $_SESSION[self::BDC_USER_CAPTCHA_CONFIG] = $this->maybeSerialize($configs);
    }

    /**
     * Get contents of config file.
     *
     * @return string
     */
    private function getConfigContents()
    {
        return $this->sanitizeConfigContents($this->filePath);
    }

    /**
     * Use class_exists('CaptchaConfiguration') to wrap methods in config file,
     * therefore we're still able to get the captcha config while Captcha library is not loaded.
     *
     * @param string  $contents
     * @return string
     */
    private function wrapClassExistsAroundMethods($contents)
    {
        $pattern = "/(=>|=)([\s*\(*\s*]*\w+::)/i";
        $replacement = "$1!class_exists('CaptchaConfiguration')? null : $2";
        return preg_replace($pattern, $replacement, $contents);
    }

    /**
     * Santinize config contents.
     * 
     * @param string  $filePath
     * @return string
     */
    private function sanitizeConfigContents($filePath)
    {
        // strip comments and whitespace
        $contents = php_strip_whitespace($filePath);

        // remove PHP tags
        $contents = ltrim($contents, '<?php');
        $contents = rtrim($contents, '?>');

        // remove other code are not necessary
        $contents = preg_replace("/if.*\(.*!.*class_exists.*}/i", '', $contents, 1);

        return $contents;
    }

    /**
     * @return string
     */
    private function maybeSerialize($data)
    {
        if (is_object($data) || is_array($data)) {
            return serialize($data);
        }
        return $data;
    }

    /**
     * @return object|string
     */
    private function maybeUnserialize($data)
    {
        if (@unserialize($data) !== false) {
            return @unserialize($data);
        }
        return $data;
    }
}

<?php namespace CakeCaptcha\Controller;

use Cake\Controller\Controller;
use CakeCaptcha\Config\Path;
use CakeCaptcha\Helpers\HttpHelper;

class CaptchaResourceController extends Controller {
    
    /**
     * Get contents of Captcha resources when BotDetect Library is located inside the package (js, css, gif files).
     *
     * @param string  $p_FileName
     */
    public function GetResource($p_FileName) {
    	$this->autoRender = false;

        $resourcePath = realpath(Path::GetPublicDirPathInLibrary() . $p_FileName);

        if (!is_readable($resourcePath)) {
            HttpHelper::BadRequest('command');
        }
        
        // allow caching
        HttpHelper::AllowCache();
        
        // captcha resource file information
     	$fileInfo = pathinfo($resourcePath);
        $fileLength = filesize($resourcePath);
        $mimeType = self::GetMimeType($fileInfo['extension']);

        header("Content-Type: {$mimeType}");
        header("Content-Length: {$fileLength}");
        echo (file_get_contents($resourcePath));
        exit;
    }

    /**
     * Mime type information.
     *
     * @param string  $p_Ext
     * @return string
     */
    private static function GetMimeType($p_Ext) {
        $mimes = [
            'css' => 'text/css',
            'gif' => 'image/gif',
            'js'  => 'application/x-javascript'
        ];
        
        return (in_array($p_Ext, array_keys($mimes))) ? $mimes[$p_Ext] : '';
    }
    
}

<?php namespace CakeCaptcha\Controller;

use Cake\Controller\Controller;
use CakeCaptcha\Helpers\CaptchaResourceHelper;

class CaptchaResourceController extends Controller {

    /**
     * Get the contents of BotDetect Library resource file.
     * 
     * @param string  $p_FileName
     */
    public function GetResource($p_FileName) {
        $this->autoRender = false;
        return CaptchaResourceHelper::GetResource($p_FileName);
    }
}

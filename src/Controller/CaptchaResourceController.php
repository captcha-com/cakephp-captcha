<?php namespace CakeCaptcha\Controller;

use Cake\Controller\Controller;
use CakeCaptcha\Helpers\CaptchaResourceHelper;

class CaptchaResourceController extends Controller {

	public function GetResource($p_FileName) {
		$this->autoRender = false;
		$resource = CaptchaResourceHelper::GetResource($p_FileName);
		return $resource;
	}
}

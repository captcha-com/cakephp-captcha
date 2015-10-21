<?php namespace CakeCaptcha\Controller;

use Cake\Controller\Controller;
use CakeCaptcha\Helpers\CaptchaHandlerHelper;

class CaptchaHandlerController extends Controller {

	// display captcha handler: image, sound,...
	public function index() {
		$this->autoRender = false;
		$handler = new CaptchaHandlerHelper();
		$handler->GetCaptchaResponse();
	}
}

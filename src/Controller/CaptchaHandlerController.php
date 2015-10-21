<?php namespace CakeCaptcha\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use CakeCaptcha\Helpers\CaptchaHandlerHelper;

class CaptchaHandlerController extends AppController {

	public function beforeFilter(Event $event) {
		if (property_exists($this, 'Auth')) {
			$this->Auth->allow('index');
		}
	}

	// display captcha handler: image, sound, etc.
	public function index() {
		$this->autoRender = false;
		$handler = new CaptchaHandlerHelper();
		$handler->GetCaptchaResponse();
	}
}

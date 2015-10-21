<?php namespace CakeCaptcha\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use CakeCaptcha\Helpers\CaptchaHandlerHelper;

class CaptchaHandlerController extends AppController {
    
    /*
     * Allow access to index action of this controller while using CakePHP Auth component.
     * 
     * @return void
     */
    public function beforeFilter(Event $event) {
        if (property_exists($this, 'Auth')) {
            $this->Auth->allow('index');
        }
    }

    /**
     * Handle request from querystring such as getting image, getting sound, etc.
     */
    public function index() {
        $this->autoRender = false;
        $handler = new CaptchaHandlerHelper();
        $handler->GetCaptchaResponse();
    }
}

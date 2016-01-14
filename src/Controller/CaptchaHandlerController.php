<?php

namespace CakeCaptcha\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use CakeCaptcha\Support\Path;

class CaptchaHandlerController extends AppController
{
    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize()
    {
        if ($this->isGetResourceContentsRequest()) {
            // validate filename
            $filename = $this->request->query('get');
            if (!preg_match('/^[a-z-]+\.(css|gif|js)$/', $filename)) {
                $this->badRequest('Invalid file name.');
            }
        } else {
            // validate captcha id and load CaptchaComponent
            $captchaId = $this->request->query('c');
            if (!is_null($captchaId) && preg_match('/^(\w+)$/ui', $captchaId)) {
                $this->loadComponent('CakeCaptcha.Captcha', [
                    'captchaConfig' => $captchaId
                ]);
            } else {
                $this->badRequest('Invalid captcha id.');
            }
        }
    }

    /**
     * Allow access to index action of this controller while using CakePHP Auth component.
     * 
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        if (property_exists($this, 'Auth')) {
            $this->Auth->allow('index');
        }
    }

    /**
     * Handle request from querystring such as getting image, getting sound, etc.
     */
    public function index()
    {
        $this->autoRender = false;

        if ($this->isGetResourceContentsRequest()) {
            // getting contents of css, js, and gif files.
            $this->getResourceContents();
        } else {
            // getting captcha image, sound, validation result
            if (is_null($this->Captcha)) {
                \BDC_HttpHelper::BadRequest('captcha');
            }

            $commandString = $this->request->query('get');
            if (!\BDC_StringHelper::HasValue($commandString)) {
                \BDC_HttpHelper::BadRequest('command');
            }

            $command = \BDC_CaptchaHttpCommand::FromQuerystring($commandString);
            switch ($command) {
                case \BDC_CaptchaHttpCommand::GetImage:
                    $responseBody = $this->getImage();
                    break;
                case \BDC_CaptchaHttpCommand::GetSound:
                    $responseBody = $this->getSound();
                    break;
                case \BDC_CaptchaHttpCommand::getValidationResult:
                    $responseBody = $this->getValidationResult();
                    break;
                default:
                    \BDC_HttpHelper::BadRequest('command');
            }

            // disallow audio file search engine indexing
            header('X-Robots-Tag: noindex, nofollow, noarchive, nosnippet');
            echo $responseBody; exit;
        }
    }

    /**
     * Get contents of Captcha resources (js, css, gif files).
     *
     * @return string
     */
    public function getResourceContents()
    {
        $filename = $this->request->query('get');

        $resourcePath = realpath(Path::getPublicDirPathInLibrary() . $filename);

        if (!is_file($resourcePath)) {
            $this->badRequest(sprintf('File "%s" could not be found.', $filename));
        }

        $mimeTypes = ['css' => 'text/css', 'gif' => 'image/gif', 'js' => 'application/x-javascript'];

        // captcha resource file information
        $fileInfo = pathinfo($resourcePath);
        $fileLength = filesize($resourcePath);
        $mimeType = $mimeTypes[$fileInfo['extension']];

        header("Content-Type: {$mimeType}");
        header("Content-Length: {$fileLength}");
        echo (file_get_contents($resourcePath)); exit;
    }

    /**
     * Generate a Captcha image.
     *
     * @return image
     */
    public function getImage()
    {
        if (is_null($this->Captcha)) {
            \BDC_HttpHelper::BadRequest('captcha');
        }

        // identifier of the particular Captcha object instance
        $instanceId = $this->getInstanceId();
        if (is_null($instanceId)) {
            \BDC_HttpHelper::BadRequest('instance');
        }

        // response headers
        \BDC_HttpHelper::DisallowCache();

        // response MIME type & headers
        $mimeType = $this->Captcha->CaptchaBase->ImageMimeType;
        header("Content-Type: {$mimeType}");

        // we don't support content chunking, since image files
        // are regenerated randomly on each request
        header('Accept-Ranges: none');

        // image generation
        $rawImage = $this->Captcha->CaptchaBase->GetImage($instanceId);
        $this->Captcha->CaptchaBase->SaveCodeCollection();

        $length = strlen($rawImage);
        header("Content-Length: {$length}");
        return $rawImage;
    }

    /**
     * Generate a Captcha sound.
     *
     * @return image
     */
    public function getSound()
    {
        if (is_null($this->Captcha)) {
            \BDC_HttpHelper::BadRequest('captcha');
        }

        // identifier of the particular Captcha object instance
        $instanceId = $this->getInstanceId();
        if (is_null($instanceId)) {
            \BDC_HttpHelper::BadRequest('instance');
        }

        // response headers
        \BDC_HttpHelper::SmartDisallowCache();

        // response MIME type & headers
        $mimeType = $this->Captcha->CaptchaBase->SoundMimeType;
        header("Content-Type: {$mimeType}");
        header('Content-Transfer-Encoding: binary');

        // sound generation
        $rawSound = $this->Captcha->CaptchaBase->GetSound($instanceId);
        return $rawSound;
    }

    /**
     * The client requests the Captcha validation result (used for Ajax Captcha validation).
     *
     * @return json
     */
    public function getValidationResult()
    {
        if (is_null($this->Captcha)) {
            \BDC_HttpHelper::BadRequest('captcha');
        }

        // identifier of the particular Captcha object instance
        $instanceId = $this->getInstanceId();
        if (is_null($instanceId)) {
            \BDC_HttpHelper::BadRequest('instance');
        }

        $mimeType = 'application/json';
        header("Content-Type: {$mimeType}");

        // code to validate
        $userInput = $this->getUserInput();

        // JSON-encoded validation result
        $result = $this->Captcha->AjaxValidate($userInput, $instanceId);
        $this->Captcha->CaptchaBase->Save();

        $resultJson = $this->getJsonValidationResult($result);

        return $resultJson;
    }

    /**
     * @return string
     */
    private function getInstanceId()
    {
        $instanceId = $this->request->query('t');
        if (!\BDC_StringHelper::HasValue($instanceId) ||
            !\BDC_CaptchaBase::IsValidInstanceId($instanceId)
        ) {
            return;
        }
        return $instanceId;
    }

    /**
     * Extract the user input Captcha code string from the Ajax validation request.
     *
     * @return string
     */
    private function getUserInput()
    {
        // BotDetect built-in Ajax Captcha validation
        $input = $this->request->query('i');

        if (is_null($input)) {
            // jQuery validation support, the input key may be just about anything,
            // so we have to loop through fields and take the first unrecognized one
            $recognized = array('get', 'c', 't', 'd');
            foreach ($this->request->query as $key => $value) {
                if (!in_array($key, $recognized)) {
                    $input = $value;
                    break;
                }
            }
        }

        return $input;
    }

    /**
     * Encodes the Captcha validation result in a simple JSON wrapper.
     *
     * @return string
     */
    private function getJsonValidationResult($result)
    {
        $resultStr = ($result ? 'true': 'false');
        return $resultStr;
    }

    /**
     * @return bool
     */
    private function isGetResourceContentsRequest()
    {
        $http_get_data = $this->request->query;
        return array_key_exists('get', $http_get_data) && !array_key_exists('c', $http_get_data);
    }

    /**
     * Throw a bad request.
     *
     * @param string  $message
     * @return void
     */
    private function badRequest($message)
    {
        while (ob_get_contents()) { ob_end_clean(); }
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: text/plain');
        echo $message;
        exit;
    }
}

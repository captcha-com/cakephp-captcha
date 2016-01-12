<?php

$baseUrl = \CakeCaptcha\CakePHPInformation::getBaseUrl();

$BotDetect = \CaptchaConfiguration::GetSettings();

$BotDetect->HandlerUrl = $baseUrl . '/captcha-handler';

// use Laravel session to store persist Captcha codes and other Captcha data
// $BotDetect->SaveFunctionName = 'CAKE_Session_Save';
// $BotDetect->LoadFunctionName = 'CAKE_Session_Load';
// $BotDetect->ClearFunctionName = 'CAKE_Session_Clear';

\CaptchaConfiguration::SaveSettings($BotDetect);

// // re-define custom session handler functions
// function CAKE_Session_Save($key, $value)
// {
//     // save the given value with the given string key
//     \Session::put($key, serialize($value));
// }

// function CAKE_Session_Load($key)
// {
//     // load persisted value for the given string key
//     if (\Session::has($key)) {
//         return unserialize(\Session::get($key)); // NOTE: returns false in case of failure
//     }
// }

// function CAKE_Session_Clear($key)
// {
//     // clear persisted value for the given string key
//     if (\Session::has($key)) {
//         \Session::remove($key);
//     }
// }

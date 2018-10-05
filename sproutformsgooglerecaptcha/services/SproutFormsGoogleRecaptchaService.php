<?php
/**
 * Sprout Forms Google reCAPTCHA plugin for Craft CMS
 *
 * SproutFormsGoogleRecaptcha Service
 *
 * @author    Nicholas O&#39;Donnell
 * @copyright Copyright (c) 2016 Nicholas O&#39;Donnell
 * @link      http://picdorsey.com
 * @package   SproutFormsGoogleRecaptcha
 * @since     2.0.1
 */

namespace Craft;

require_once __DIR__ . '/../vendor/autoload.php';

class SproutFormsGoogleRecaptchaService extends BaseApplicationComponent
{
    /**
     * Gets HTML code to generate ReCAPATCHA
     * @return [object] template
     */
    public function getReCapatchaCode()
    {
        $siteKey = craft()->plugins->getPlugin('sproutFormsGoogleRecaptcha')->getSettings()->siteKey;
        $content = '<div class="g-recaptcha" data-sitekey="' . $siteKey . '" id="' . craft()->security->generateRandomString(8) . '"></div>';
        return TemplateHelper::getRaw($content);
    }

    /**
     * Validates
     * @param  [object] $entry
     * @return [bool] ReCAPATCHA vailidated
     */
    public function validateReCapatcha($entry)
    {
        $post = craft()->request->getPost();
        $secretKey = craft()->plugins->getPlugin('sproutFormsGoogleRecaptcha')->getSettings()->secreyKey;

        $token = isset($post['g-recaptcha-response']) ? $post['g-recaptcha-response'] : null;

        if ($token == null) {
            return false;
        }

        $recaptcha = new \ReCaptcha\ReCaptcha($secretKey, new \ReCaptcha\RequestMethod\CurlPost());
        $resp = $recaptcha->verify($token);

        return $resp->isSuccess();
    }
}

<?php
/**
 * Sprout Forms Google reCAPTCHA plugin for Craft CMS
 *
 * Adds Google reCAPTCHA to sprout forms.
 *
 * @author    Nicholas O&#39;Donnell
 * @copyright Copyright (c) 2016 Nicholas O&#39;Donnell
 * @link      http://picdorsey.com
 * @package   SproutFormsGoogleRecaptcha
 * @since     2.0.1
 */

namespace Craft;

class SproutFormsGoogleRecaptchaPlugin extends BasePlugin
{
    /**
     * @return mixed
     */
    public function init()
    {
        $this->_hooks();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
         return Craft::t('Sprout Forms Google reCAPTCHA');
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return Craft::t('Adds Google reCAPTCHA to sprout forms.');
    }

    /**
     * @return string
     */
    public function getDocumentationUrl()
    {
        return 'https://github.com/picdorsey/craft-sproutformsgooglerecaptcha/blob/master/readme.md';
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return '2.0.1';
    }

    /**
     * @return string
     */
    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/picdorsey/craft-sproutformsgooglerecaptcha/master/releases.json';
    }

    /**
     * @return string
     */
    public function getSchemaVersion()
    {
        return '1.0.1';
    }

    /**
     * @return string
     */
    public function getDeveloper()
    {
        return 'Piccirilli Dorsey, Inc.';
    }

    /**
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'http://picdorsey.com';
    }

    /**
     * @return bool
     */
    public function hasCpSection()
    {
        return false;
    }

    /**
     */
    public function onBeforeInstall()
    {
    }

    /**
     */
    public function onAfterInstall()
    {
    }

    /**
     */
    public function onBeforeUninstall()
    {
    }

    /**
     */
    public function onAfterUninstall()
    {
    }

    /**
     * @return array
     */
    protected function defineSettings()
    {
        return array(
            'siteKey' => [
                AttributeType::String,
                'label' => 'Site Key',
                'default' => '',
                'required' => true
            ],
            'secreyKey' => [
                AttributeType::String,
                'label' => 'Secret Key',
                'default' => '',
                'required' => true
            ],
            'enabledForms' => [
                AttributeType::Mixed,
                'label' => 'Enabled Forms',
                'default' => $this->_getForms()
            ],
        );
    }

    /**
     * @return mixed
     */
    public function getSettingsHtml()
    {
       return craft()->templates->render('sproutformsgooglerecaptcha/SproutFormsGoogleRecaptcha_Settings', array(
           'settings' => $this->getSettings()
       ));
    }

    /**
     * @param mixed $settings  The Widget's settings
     *
     * @return mixed
     */
    public function prepSettings($settings)
    {
        return $settings;
    }


    /**
     * Hook into core/plugin events
     */
    private function _hooks()
    {
        craft()->templates->hook('sproutForms.modifyForm', function (&$context) {
            $handle = $context['entry']->form->handle;

            // if (! in_array($handle, $this->getSettings()->enabledForms)) {
            //     return;
            // }

            return craft()->sproutFormsGoogleRecaptcha->getReCapatchaCode();
        });

        craft()->on('sproutForms.onBeforeSaveEntry', function (Event $event) {
            $entry = $event->params['entry'];
            $handle = $entry->form->handle;
            $isNewEntry = $event->params['isNewEntry'];

            // if (! in_array($handle, $this->getSettings()->enabledForms)) {
            //     return;
            // }

            if ($isNewEntry && ! craft()->sproutFormsGoogleRecaptcha->validateReCapatcha($entry)) {
                echo craft()->templates->render('sproutformsgooglerecaptcha/SproutFormsGoogleRecaptcha_Error');
                die();
            }
        });
    }

    /**
     * Get all forms
     */
    private function _getForms()
    {
        $forms = sproutForms()->forms->getAllForms();
        $enabledForms = [];

        foreach ($forms as $form) {
            $enabledForms[] = $form->handle;
        }

        return $enabledForms;
    }
}

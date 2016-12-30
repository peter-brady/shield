<?php

namespace Craft;

class PasswordControlController extends BaseController
{

    public function actionSettings()
    {
        $plugin = craft()->plugins->getPlugin('passwordcontrol');
        $settings = $plugin->getSettings();

        $this->renderTemplate('passwordcontrol/settings', array('settings' => $settings));
    }

    public function actionConstraintSettings()
    {
        $plugin = craft()->plugins->getPlugin('passwordcontrol');
        $settings = $plugin->getSettings();

        $this->renderTemplate('passwordcontrol/settings_constraints', array('settings' => $settings));
    }

    public function actionExpirationSettings()
    {
        $plugin = craft()->plugins->getPlugin('passwordcontrol');
        $settings = $plugin->getSettings();

        $this->renderTemplate('passwordcontrol/settings_expiration', array('settings' => $settings));
    }

    public function actionEmailsSettings()
    {
        $plugin = craft()->plugins->getPlugin('passwordcontrol');
        $settings = $plugin->getSettings();

        $this->renderTemplate('passwordcontrol/settings_emails', array('settings' => $settings));
    }

    public function actionHistorySettings()
    {
        $plugin = craft()->plugins->getPlugin('passwordcontrol');
        $settings = $plugin->getSettings();

        $this->renderTemplate('passwordcontrol/settings_history', array('settings' => $settings));
    }

    /**
     * Saves the system user settings.
     *
     * @return null
     */
    public function actionSaveSettings()
    {

        $this->requirePostRequest();

        $settings = array();
        $settings['enabled'] = craft()->request->getPost('enabled');
        $settings['constraint_length'] = craft()->request->getPost('constraint_length');
        $settings['constraint_numbers'] = craft()->request->getPost('constraint_numbers');
        $settings['constraint_letters'] = craft()->request->getPost('constraint_letters');
        $settings['constraint_lowercase'] = craft()->request->getPost('constraint_lowercase');
        $settings['constraint_uppercase'] = craft()->request->getPost('constraint_uppercase');
        $settings['constraint_punctuation'] = craft()->request->getPost('constraint_punctuation');
        $settings['constraint_numbers'] = craft()->request->getPost('constraint_numbers');

        if (craft()->plugins->savePluginSettings(craft()->plugins->getPlugin('passwordcontrol'), $settings))
        {
            craft()->userSession->setNotice(Craft::t('Password settings saved.'));
            $this->redirectToPostedUrl();
        }
        else {
            craft()->userSession->setError(Craft::t('Couldn\'t save password settings.'));

            // Send the settings back to the template
            craft()->urlManager->setRouteVariables(array(
                'settings' => $settings
            ));
        }
    }
}
<?php

namespace Craft;

class PasswordControl_SettingsController extends BaseController
{
    protected $_settings;

    /**
     *
     */
    function init()
    {
        $plugin = craft()->plugins->getPlugin('passwordcontrol');
        $this->_settings = $plugin->getSettings();
    }

    /**
     *
     */
    public function actionGeneral(array $variables = array())
    {
        $this->renderTemplate('passwordcontrol/settings',
            array('settings' => $this->_settings)
        );
    }

    /**
     *
     */
    public function actionConstraints()
    {
        $this->renderTemplate('passwordcontrol/settings_constraints',
            array('settings' => $this->_settings)
        );
    }

    /**
     *
     */
    public function actionExpiration()
    {
        $this->renderTemplate('passwordcontrol/settings_expiration',
            array('settings' => $this->_settings)
        );
    }

    /**
     *
     */
    public function actionEmails()
    {
        $this->renderTemplate('passwordcontrol/settings_emails',
            array('settings' => $this->_settings)
        );
    }

    /**
     * Saves the system user settings.
     *
     * @return null
     */
    public function actionSave()
    {

        $this->requirePostRequest();

        $settings = $this->_settings;

        //
        switch (craft()->request->getPost('settingsForm'))
        {
            case 'constraints':
                $settings['enabled'] = craft()->request->getPost('enabled');
                $settings['constraint_length'] = craft()->request->getPost('constraint_length');
                $settings['constraint_numbers'] = craft()->request->getPost('constraint_numbers');
                $settings['constraint_letters'] = craft()->request->getPost('constraint_letters');
                $settings['constraint_lowercase'] = craft()->request->getPost('constraint_lowercase');
                $settings['constraint_uppercase'] = craft()->request->getPost('constraint_uppercase');
                $settings['constraint_punctuation'] = craft()->request->getPost('constraint_punctuation');
                $settings['constraint_numbers'] = craft()->request->getPost('constraint_numbers');
                break;
        }

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
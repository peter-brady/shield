<?php

namespace Craft;

class ShieldController extends BaseController
{
    protected $_settings;

    /**
     *
     */
    function init()
    {
        $this->_settings = craft()->shield->getSettings();
    }

    /**
     *
     */
    public function actionSettings(array $variables = array())
    {
        $this->renderTemplate('shield/settings',
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

        $settings['name'] = craft()->request->getPost('name');
        $settings['pass'] = craft()->request->getPost('pass');
        $settings['enabled_sitewide'] = craft()->request->getPost('enabled_sitewide');
        $settings['enabled_cp'] = craft()->request->getPost('enabled_cp');
        $settings['enabled_cli'] = craft()->request->getPost('enabled_cli');
        $settings['paths'] = craft()->request->getPost('paths');
        $settings['text_unauthorised'] = craft()->request->getPost('text_unauthorised');
        
        $plugin = craft()->plugins->getPlugin('shield');
        if (craft()->plugins->savePluginSettings($plugin, $settings))
        {
            craft()->userSession->setNotice(Craft::t('Shield settings saved.'));
            $this->redirectToPostedUrl();
        }
        else {
            craft()->userSession->setError(Craft::t('Couldn\'t save Shield settings.'));

            // Send the settings back to the template
            craft()->urlManager->setRouteVariables(array(
                'settings' => $settings
            ));
        }
    }
}
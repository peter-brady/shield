<?php

namespace Craft;


class PasswordControlPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Password Control');
    }

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'Peter Brady';
    }

    public function getDeveloperUrl()
    {
        return 'https://www.peterbrady.co.uk';
    }

    protected function defineSettings()
    {
        return array(
            'enabled' => array(AttributeType::Bool, 'default' => 0),
            'constraint_length' => array(AttributeType::Number),
            'constraint_letters' => array(AttributeType::Number),
            'constraint_lowercase' => array(AttributeType::Number),
            'constraint_punctuation' => array(AttributeType::Number),
            'constraint_uppercase' => array(AttributeType::Number),
            'constraint_numbers' => array(AttributeType::Number),
        );
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render('passwordcontrol/settings', array(
            'settings' => $this->getSettings()
        ));
    }

    /**
     * @return string
     */
    public function getSettingsUrl()
    {
        return 'password-control';
    }

    /**
     * @return array
     */
    public function registerCpRoutes()
    {
        return [
            'password-control' => ['action' => 'passwordControl/settings'],
            'password-control/constraints' => ['action' => 'passwordControl/constraintSettings'],
            'password-control/history' => ['action' => 'passwordControl/historySettings'],
            'password-control/expiration' => ['action' => 'passwordControl/expirationSettings'],
            'password-control/emails' => ['action' => 'passwordControl/emailsSettings'],
        ];
    }
}

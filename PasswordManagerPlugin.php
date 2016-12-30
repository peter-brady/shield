<?php

namespace Craft;


class PasswordManagerPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Password Manager');
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
        return craft()->templates->render('passwordmanager/settings', array(
            'settings' => $this->getSettings()
        ));
    }

    /**
     * @return string
     */
    public function getSettingsUrl()
    {
        return 'password-manager';
    }

    /**
     * @return array
     */
    public function registerCpRoutes()
    {
        return [
            'password-manager' => ['action' => 'passwordManager/settings'],
            'password-manager/constraints' => ['action' => 'passwordManager/constraintSettings'],
            'password-manager/history' => ['action' => 'passwordManager/historySettings'],
            'password-manager/expiration' => ['action' => 'passwordManager/expirationSettings'],
            'password-manager/emails' => ['action' => 'passwordManager/emailsSettings'],
        ];
    }
}

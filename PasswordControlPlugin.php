<?php

namespace Craft;


class PasswordControlPlugin extends BasePlugin
{
    /**
     * @return null|string
     */
    public function getName()
    {
        return Craft::t('Password Control');
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getDeveloper()
    {
        return 'Peter Brady';
    }

    /**
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'https://www.peterbrady.co.uk';
    }

    /**
     * @return array
     */
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

    /**
     * @return string
     */
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
        return array(
            'password-control' => array('action' => 'passwordControl/settings/general'),
            'password-control/constraints' => array('action' => 'passwordControl/settings/constraints'),
            'password-control/history' => array('action' => 'passwordControl/settings/history'),
            'password-control/expiration' => array('action' => 'passwordControl/settings/expiration'),
            'password-control/emails' => array('action' => 'passwordControl/settings/emails'),
        );
    }
}

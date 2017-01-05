<?php

namespace Craft;


class ShieldPlugin extends BasePlugin
{
    /**
     * @return null|string
     */
    public function getName()
    {
        return Craft::t('Shield');
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
            'name' => array(AttributeType::String, 'default' => ''),
            'pass' => array(AttributeType::String, 'default' => ''),
            'enabled_sitewide' => array(AttributeType::Bool, 'default' => 0),
            'enabled_cp' => array(AttributeType::Bool, 'default' => 0),
            'paths' => array(AttributeType::Mixed, 'default' => ''),
            'text_unauthorised' => array(AttributeType::String, array('default' => '')),
        );
    }

    /**
     * @return string
     */
    public function getSettingsHtml()
    {
        return craft()->templates->render('shield/settings', array(
            'settings' => $this->getSettings()
        ));
    }

    /**
     * @return string
     */
    public function getSettingsUrl()
    {
        return 'shield';
    }

    /**
     * @return array
     */
    public function registerCpRoutes()
    {
        return array(
            'shield' => array('action' => 'shield/settings'),
        );
    }

    /**
     * [init description]
     * @return [type] [description]
     */
    public function init()
    {
        // Get the Shield settings.
        $settings = $this->getSettings();

        // Don't do anything unless we have a username
        // and a password and this request is not a 
        // console request. 
        if(!$settings->name || !$settings->pass || craft()->shield->_isConsoleRequest())
        {
            return;
        }

        // If the sitewide shield is enabled, check if we
        // need to present a HTTP Auth challenge. Then return, as
        // none of the other shield need apply.
        if(craft()->shield->_shieldSitewide())
        {
          return;
        }


    }
}

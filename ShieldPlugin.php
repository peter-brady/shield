<?php

/**
 * 
 */

namespace Craft;


class ShieldPlugin extends BasePlugin
{
    /**
     * @return string
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
            'enabled_control_panel' => array(AttributeType::Bool, 'default' => 0),
            'enabled_front_end' => array(AttributeType::Bool, 'default' => 0),
            'enabled_paths' => array(AttributeType::Bool, 'default' => 0),
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
     * Checks if the the shield is required for this page request.
     */
    public function init()
    {
        parent::init();

        /* Set the Shield settings to a variable. */
        $settings = $this->getSettings(); // !!! Check to see if I need this !!!

        /**
         * Return if we don't have have a username, a 
         * password AND this request is not a console request. 
         */
        if(!$settings->name || !$settings->pass || craft()->isConsole())
        {
            return;
        }

        /**
         * If the sitewide shield is enabled, check to see if we should
         * present a HTTP Auth challenge for this page request.
         * If so, return, as none of the other shields need apply.
         */
        if(craft()->shield->_shieldSitewide())
        {
          return;
        }

        // If the Control Panel shield is enabled, check to see
        // if we should present a HTTP Auth challenge for this
        // page request. 
        craft()->shield->_shieldControlPanel();

        /**
         * If the Front End shield is enabled, check to see if we
         * should present a HTTP Auth challenge for this page request.
         */
        craft()->shield->_shieldFrontEnd();

        /**
         * If path shields are enable, check to see if we should
         * present a HTTP Auth challenge for this page request. 
         */
        craft()->shield->_shieldPaths();
    }
}

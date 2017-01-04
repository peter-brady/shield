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
            'enabled_cli' => array(AttributeType::Bool, 'default' => 0),
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
        // and a password. 
        if(!$settings->name && !$settings->pass)
        {
            return;
        }
        
        $user = $settings->name;
        $pass = $settings->pass;

        // If sitewide is enabled.
        if($settings->enabled_sitewide)
        {

            if (!empty($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])
              && $_SERVER['PHP_AUTH_USER'] == $user
              && $_SERVER['PHP_AUTH_PW']   == $pass) {
                return;
                }

          $print = null;
          header(sprintf('WWW-Authenticate: Basic realm="%s"', strtr($print, array('[user]' => $user, '[pass]' => $pass))));
          header('HTTP/1.0 401 Unauthorized');
          echo $settings->text_unauthorised;
          exit;
        }
    }
}

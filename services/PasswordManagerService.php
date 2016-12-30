<?php

namespace Craft;

class PasswordManagerService extends BaseApplicationComponent
{
    /**
     * Setup
     */
    public function init()
    {
        $this->_plugin = craft()->plugins->getPlugin('passwordManager');
    }

    public function getSettings()
    {
        return $this->_plugin->getSettings();
    }
}
<?php

namespace Craft;

class PasswordControlService extends BaseApplicationComponent
{
    /**
     * Setup
     */
    public function init()
    {
        $this->_plugin = craft()->plugins->getPlugin('passwordControl');
    }

    public function getSettings()
    {
        return $this->_plugin->getSettings();
    }
}
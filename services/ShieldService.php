<?php

namespace Craft;

class ShieldService extends BaseApplicationComponent
{
	function getSettings()
	{
		// Get Shield settings.
        $plugin = craft()->plugins->getPlugin('shield');
        return $plugin->getSettings();
	}
}

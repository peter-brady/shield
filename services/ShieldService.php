<?php

namespace Craft;

class ShieldService extends BaseApplicationComponent
{
	protected $_settings;

    /**
     *
     */
    function init()
    {
        // Get Shield settings.
        $plugin = craft()->plugins->getPlugin('shield');
        $this->_settings = $plugin->getSettings();
    }

	/**
	 * [getSettings description]
	 * @return [type] [description]
	 */
	function getSettings()
	{
		return $this->_settings;	
	}

	/**
	 * [_shieldPassed description]
	 * @return [type] [description]
	 */
	function _shieldPassed()
	{
		if (
			!empty($_SERVER['PHP_AUTH_USER'])
			&& isset($_SERVER['PHP_AUTH_PW'])
	        && $_SERVER['PHP_AUTH_USER'] == $this->_settings->name
	        && $_SERVER['PHP_AUTH_PW']   == $this->_settings->pass)
		{
			return true;
		}
	}

	/**
	 * [_shieldFailed description]
	 * @return [type] [description]
	 */
	function _shieldFailed()
	{
		$print = null;
		$name = $this->_settings->name;
		$pass = $this->_settings->pass;
		header(sprintf('WWW-Authenticate: Basic realm="%s"', strtr($print, array('[name]' => $name, '[pass]' => $pass))));
		header('HTTP/1.0 401 Unauthorized');
		echo $this->_settings->text_unauthorised;
		exit;
	}

	/**
	 * _isSitewideEnabled
	 * 
	 * @return bool true if Shield settings are set to 'Sitewide enabled'. 
	 */
	function _shieldSitewide()
	{
		if($this->_settings->enabled_sitewide)
		{
			if ($this->_shieldPassed())
			{
	        	return true;
	        }

	        $this->_shieldFailed();
        }
	}

	/**
	 * _isCpEnabled
	 * 
	 * @return bool true if Shield settings are set to 'Control Panel enabled'.
	 */
	function _cpTriggered()
	{
		return false;	
	}

	/**
	 * _isCliEnabled
	 * 
	 * @return bool true if Shield settings are set to 'Console enabled'.
	 */
	function _cliTriggered()
	{
		return false;
	}

	/**
	 * _isPathsEnabled
	 * 
	 * @return bool true if Shield settings has paths present.
	 */
	function _pathsTriggered()
	{
		return false;
	}
}

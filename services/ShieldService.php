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
	public function getSettings()
	{
		return $this->_settings;	
	}

	/**
	 * [_shieldPassed description]
	 * @return [type] [description]
	 */
	public function _shieldPassed()
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
	public function _shieldFailed()
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
	public function _shieldSitewide()
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
	 * 
	 */
	public function _shieldControlPanel()
	{
		if($this->_settings->enabled_control_panel && craft()->request->isCpRequest())
		{
			if ($this->_shieldPassed())
			{
	        	return true;
	        }

	        $this->_shieldFailed();
		}
	}

	/**
	 * 
	 */
	public function _shieldFrontEnd()
	{
		if($this->_settings->enabled_front_end &&  craft()->request->isSiteRequest())
		{
			if ($this->_shieldPassed())
			{
	        	return true;
	        }

	        $this->_shieldFailed();
		}
	}

	/**
	 * 
	 */
	public function _shieldPaths()
	{
		if($this->_settings->enabled_paths && $this->_settings->paths)
		{
			if (craft()->request->isCpRequest()) {
				$path = craft()->config->get('cpTrigger') . '/' . craft()->request->getPath();
			} else {
				$path = craft()->request->getPath();
			}

			if(!$path)
			{
				$path = '/';
			}

			$patterns = $this->_settings->paths;
			
			if($this->_pathPatternMatch($path, $patterns))
			{
				if ($this->_shieldPassed())
			{
	        	return true;
	        }

	        	$this->_shieldFailed();
			}
		}
	}

	private function _pathPatternMatch($path, $patterns)
	{

		$to_replace = array(
	      '/(\r\n?|\n)/', // newlines
	      '/\\\\\*/', // asterisks
	    );

	    $replacements = array(
	      '|',
	      '.*',
	     );

	    $patterns_quoted = preg_quote($patterns, '/');
	    $regexps[$patterns] = '/^(' . preg_replace($to_replace, $replacements, $patterns_quoted) . ')$/';

	    return (bool) preg_match($regexps[$patterns], $path);
	}
}

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
		header(sprintf('WWW-Authenticate: Basic realm="%s"', strtr($print, array('[name]' => $name, '[pass]' => $pass)))); // !!! Check to see if I need to change this !!!
		header('HTTP/1.0 401 Unauthorized');
		echo $this->_settings->text_unauthorised;
		exit;
	}

	/**
	 * _isSitewideEnabled
	 * 
	 * @return bool True if Shield settings are set to 'Sitewide enabled'. 
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
			/**
			 * Get the path for this page request.
			 *
			 * If it's a Control Panel request, prepend the cpTrigger
			 * value to the path. If not, just get the path.
			 */
			if (craft()->request->isCpRequest()) {
				$path = craft()->config->get('cpTrigger') . '/' . craft()->request->getPath();
			} else {
				$path = craft()->request->getPath();
			}

			/**
			 * The path returned for the homepage is an empty string.
			 *
			 * If we get this, make it a / so admins have use / is the 
			 * path textarea on the Shield settings page.
			 */
			if(!$path)
			{
				$path = '/';
			}

			/** Get the paths the admin entered in the Shield settings page. */
			$patterns = $this->_settings->paths;
			
			/**
			 * Match this page request against the patterns. If we get a
			 * match, let the user continue. Else, invoke the shield.
			 */
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

	/**
	 * 
	 * @param  string $path     The current page path.
	 * @param  string $patterns The path settings from the Shield settings page.
	 * @return bool             True if the pattern patches. False if not.
	 */
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

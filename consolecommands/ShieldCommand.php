<?php
namespace Craft;

class ShieldCommand extends BaseCommand
{

/**
	 * @return string the help information for the shell command
	 */
	public function getHelp()
	{
		return <<<EOD
USAGE
  yiic shield enable [all | sitewide | front_end | control_panel | paths]
  yiic shield disable [all | sitewide | front_end | control_panel | paths]

DESCRIPTION
  This command allows you to...

  Some more text.

PARAMETERS
 * 

EOD;
	}

	/**
	 * Console command to enable all or specific shields.
	 */
	public function actionEnable($args)
	{
		$this->_commandShieldInvoke('enable', $args);
	}

	/**
	 * Console command to disable all or specific shields.
	 */
	public function actionDisable($args)
	{
		$this->_commandShieldInvoke('disable', $args);
	}

	/**
	 * 
	 */
	private function _commandShieldInvoke($type, $args)
	{
		$participle = $type == 'enable' ? 'Enabled' : 'Disabled';

		if(!isset($args[0]))
		{
			$this->usageError("The 'shield $type' command requires parameters Ie. [all | sitewide | front_end | control_panel | paths]");
		}

		/**
		 * 
		 */
		foreach ($args as $arg) {
			switch ($arg) {
				case 'all':
					echo "Shield: $participle all shields.\n";
					break;

				case 'sitewide':
					echo "Shield: $participle sitewide shield.\n";
					break;

				case 'front_end':
					echo "Shield: $participle front end shield.\n";
					break;

				case 'control_panel':
					echo "Shield: $participle Control Panel shield.\n";
					break;

				case 'paths':
					echo "Shield: $participle paths shield.\n";
					break;

			}
		}
	}

}
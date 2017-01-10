<?php

namespace Craft;

class ShieldVariable
{

	public function getCpTrigger()
	{
		return craft()->config->get('cpTrigger'); // !!! Check to see if I need this !!!
	}
}
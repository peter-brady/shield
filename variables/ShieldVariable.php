<?php

namespace Craft;

class ShieldVariable
{

	public function getCpTrigger()
	{
		return craft()->config->get('cpTrigger');
	}
}
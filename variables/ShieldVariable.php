<?php

namespace Craft;

class ShieldVariable
{

	public function foo()
	{
		return craft()->config->get('cpTrigger');
	}
}
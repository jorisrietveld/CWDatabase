<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 2-9-15 - 15:01
 * Licence: GPLv3
 */

namespace CWDatabase\Drivers;

interface DriverInterface
{
	public function connect( Array $config );
}
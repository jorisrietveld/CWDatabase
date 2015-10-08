<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 8-10-15 - 11:18
 */

namespace CWDatabase\Helper;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;


class Logger
{
	private static $LogLevels = [
		0 => [ ],
		1 => [ LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL ],
		2 => [ LogLevel::ERROR, LogLevel::WARNING, LogLevel::NOTICE ],
		3 => [ LogLevel::INFO ],
		4 => [ LogLevel::DEBUG ]
	];

	public static function getTypeLevel( $type )
	{
		foreach( self::$LogLevels as $level => $logTypes )
		{
			if( in_array( $type, $logTypes ) )
			{
				return $level;
			}
		}

		return 0;
	}
}
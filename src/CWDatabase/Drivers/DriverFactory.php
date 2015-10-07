<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 2-9-15 - 14:59
 */

namespace CWDatabase\Drivers;

use PDO;
use InvalidArgumentException;
use CWDatabase\Drivers\MysqlDriver;
use CWDatabase\Drivers\SqlServerDriver;
use CWDatabase\Drivers\SqlightDriver;


/**
 * Class DriverFactory
 * @package CWDatabase\Drivers
 */
class DriverFactory
{
	/**
	 * @param array $config
	 *
	 * @return \CWDatabase\Drivers\MysqlDriver|\CWDatabase\Drivers\SqlightDriver|\CWDatabase\Drivers\SqlServerDriver
	 */
	public function createDriver( Array $config  )
	{
		if( !isset( $config["driver"] ))
		{
			throw new InvalidArgumentException("A database driver must be specified.");
		}

		switch( $config["driver"])
		{
			case "mysql":
				return new MysqlDriver();

			case "mssql":
				return new SqlServerDriver();

			case "sqlight":
				return new SqlightDriver();

			default:
				throw new InvalidArgumentException("Unsupported driver in configuration: {$config['driver']}.");
		}
	}
}
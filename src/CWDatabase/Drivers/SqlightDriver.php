<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 14-9-15 - 12:02
 * Licence: GPLv3
 */

namespace CWDatabase\Drivers;

use InvalidArgumentException;
use PDO;


class SqlightDriver extends Driver implements DriverInterface
{

	/**
	 * This method will connect to a Sqlight database
	 *
	 * @param array $config
	 *
	 * @return mixed
	 */
	public function connect( array $config )
	{
		$options = $this->getOptions( $config );

		/**
		 * For an SQLight in memory database
		 */
		if( $config[ 'database' ] == 'memory' )
		{
			return $this->openConnection( 'sqlite::memory:', $config, $options );
		}

		$sqlightPath = realpath( $config[ 'database' ] );

		if( $sqlightPath == false )
		{
			throw new InvalidArgumentException( "The SQLight database does not exist at: {$sqlightPath}." );
		}

		return $this->openConnection( "sqlite:{$sqlightPath}", $config, $options );
	}
}
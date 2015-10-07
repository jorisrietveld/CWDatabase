<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 2-9-15 - 14:59
 */

namespace CWDatabase\Drivers;

use DebugBar\StandardDebugBar;
use PDO;


class Driver
{
	/**
	 * This are the default database driver options for opening an connection to the database.
	 * @var array
	 */
	protected $options = [
		PDO::ATTR_EMULATE_PREPARES  => false,
		PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_CASE              => PDO::CASE_NATURAL,
		PDO::ATTR_ORACLE_NULLS      => PDO::NULL_NATURAL,
		PDO::ATTR_STRINGIFY_FETCHES => false,
	];

	/**
	 * Get the options for an connection.
	 *
	 * @param array $config
	 *
	 * @return array
	 */
	public function getOptions( Array $config )
	{
		$options = !empty( $config[ "options" ] ) ? $config[ "options" ] : [ ];

		return array_diff_key( $this->options, $options ) + $options;
	}

	/**
	 * Open an connection to an database.
	 *
	 * @param       $dsn
	 * @param array $config
	 * @param array $options
	 *
	 * @return PDO
	 */
	public function openConnection( $dsn, array $config, array $options )
	{
		$username = !empty( $config[ "username" ] ) ? $config[ "username" ] : "";
		$password = !empty( $config[ "password" ] ) ? $config[ "password" ] : "";

		return new PDO( $dsn, $username, $password, $options );
	}

	/**
	 * Set the default options for an database connection.
	 *
	 * @param array $options
	 */
	public function setDefaultOptions( Array $options )
	{
		$this->options = $options;
	}

	/**
	 * Get the default database connection options.
	 * @return array
	 */
	public function getDefaultOptions()
	{
		return $this->options;
	}

	/**
	 * Get all the installed PDO drivers.
	 * @return array
	 */
	public function getAvailablePdoDrivers()
	{
		return PDO::getAvailableDrivers();
	}

	public function debugBar( StandardDebugBar $debugBar )
	{

	}
}
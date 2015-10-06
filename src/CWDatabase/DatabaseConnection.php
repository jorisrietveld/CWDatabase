<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 21-9-15 - 8:49
 * Licence: GPLv3
 */

namespace CWDatabase;

use CWDatabase\Drivers\DriverFactory;


class DatabaseConnection
{
	protected $config =[];
	protected $driver = null;
	protected $connection = null;
	protected $queryLogger = [];

	public function __construct( Array $config = [ ] )
	{
		$this->config = $config;
	}

	/**
	 * @param array $config
	 *
	 * @return null
	 */
	public function getConnection( Array $config = [ ] )
	{
		$this->checkIfConfigIsSet( $config );
		$this->getDriver();
		$this->openConnection();

		return $this->connection;
	}

	/**
	 * Open an database connection based on the config[] property
	 */
	protected function openConnection()
	{
		if( $this->connection == null )
		{
			$this->connection = $this->driver->connect( $this->config );
		}
	}

	/**
	 * Check if there is anny configuration set to open an database connection.
	 *
	 * @param array $config
	 */
	protected function checkIfConfigIsSet( Array $config = [ ] )
	{
		if( count( $config ) == 0 && count( $this->config ) == 0 )
		{
			throw new \InvalidArgumentException( "No configuration was set for an database connection." );
		}

		array_merge( $this->config, $config );
	}

	/**
	 * Get an database driver.
	 */
	public function getDriver()
	{
		$driverFactory = new DriverFactory();

		$this->driver = $driverFactory->createDriver( $this->config );
	}

	/**
	 * With this method you can perform an raw SQL statement to the database. Only use this to execute statements that
	 * PDO::Query can't perform like setting a charset, collation, timezone or default database.
	 *
	 * @param $sql
	 *
	 * @return int
	 */
	public function rawSqlStatement( $sql )
	{
		$connection = $this->getDatabaseConnection();

		return $connection->exec( $sql );
	}

	/**
	 * With this method you can perform a raw query to the database. Do not use this to perform query's to the database
	 * with unfiltered user input because it has no security against SQL injections. It returns an PDO::Statment object.
	 *
	 * @param $sql
	 *
	 * @return \PDOStatement
	 */
	public function rawQuery( $sql )
	{
		$connection = $this->getDatabaseConnection();

		return $connection->query( $sql );
	}

	/**
	 * Perform an prepared query on the database.
	 * The values can be bound with question mark and named placeholders. example:
	 * $questionMark = [ "value1", "value2" ] or $named = [ ":id" => "value1" ":name" => "value2" ]
	 *
	 * @param $sql
	 * @param $parameters
	 */
	public function query( $sql, $parameters = [ ] )
	{
		$pdoStatement = $this->databaseConnection->prepare( $sql );

		if( count( $parameters ) )
		{
			$pdoStatement = $this->bindValues( $pdoStatement, $parameters );

		}

		if( $pdoStatement->execute() )
		{
			return $pdoStatement;
		}

		return false;
	}

	/**
	 * This method binds the $values to the Pdo statement object and returnes the altered pdo
	 * statement object.
	 *
	 * @param $pdoStatement
	 * @param $values
	 *
	 * @return mixed
	 */
	protected function bindValues( $pdoStatement, $values )
	{
		if( Arr::isAssoc( $values ) )
		{
			foreach( $values as $placeholder => $value )
			{
				$pdoStatement->bindValue( $placeholder, $value );
			}
		}
		else
		{
			foreach( $values as $key => $parameter )
			{
				$pdoStatement->bindValue( ( $key + 1 ), $parameter );
			}
		}

		return $pdoStatement;
	}

	/**
	 * Select a data set from the connected database.
	 *
	 * @param array  $columns
	 * @param        $table
	 * @param array  $where
	 * @param string $order
	 *
	 * @return object
	 */
	public function select( Array $columns, $table, Array $where = [ ], $order = "" )
	{
		// TODO: remove example.
		$whereClauseData = [ "id = :id AND name = :name", [ "id" => 1, "name" => "joris" ] ];

		$sqlColonsString = "`" . rtrim( join( "`,", $columns ), "," );

		$sql = "SELECT {$sqlColonsString} FROM {$table} ";

		// If there there is an where clause.
		if( count( $where ) )
		{
			$sql .= $where[ 0 ];
			$valuesWhereClause = $where[ 1 ];
		}

		// Add the order.
		$sql .= $order;

		return $this->query( $sql, isset( $valuesWhereClause ) );
	}
}
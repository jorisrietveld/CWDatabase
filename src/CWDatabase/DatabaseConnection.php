<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 21-9-15 - 8:49
 * Licence: GPLv3
 */

namespace CWDatabase;

use CWDatabase\Drivers\DriverFactory;
use CWDatabase\Helper\Message;
use CWDatabase\Helper\QueryLogger;
use CWDatabase\Helper\Arr;


class DatabaseConnection
{
	protected $config      = [ ];
	protected $driver      = null;
	protected $connection  = null;
	protected $queryLogger = null;

	public $logQuerys = true;

	public function __construct( Array $config = [ ] )
	{
		$this->config = $config;

		if( $this->logQuerys )
		{
			$this->queryLogger = new QueryLogger();
		}
	}

	/**
	 * @param array $config
	 *
	 * @return null
	 */
	public function getConnection( Array $config = [ ] )
	{
		$this->openConnection( $config );

		return $this->connection;
	}

	/**
	 * Open an database connection based on the config[] property
	 */
	protected function openConnection( Array $config = [ ] )
	{
		$this->checkIfConfigIsSet( $config );
		$this->getDriver();

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
		$this->openConnection();

		if( $this->logQuerys )
		{
			$this->queryLogger->log( __METHOD__, $sql );
		}

		return $this->connection->exec( $sql );
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
		$this->openConnection();

		if( $this->logQuerys )
		{
			$this->queryLogger->log( __METHOD__, $sql );
		}

		return $this->connection->query( $sql );
	}

	/**
	 * Perform an prepared query on the database.
	 * The values can be bound with question mark and named placeholders. example:
	 * $questionMark = [ "value1", "value2" ] or $named = [ ":id" => "value1" ":name" => "value2" ]
	 *
	 * @param $sql
	 * @param $parameters
	 *
	 * @return bool|mixed
	 */
	public function query( $sql, $parameters = [ ] )
	{
		$this->openConnection();

		if( $this->logQuerys )
		{
			$this->queryLogger->log( __METHOD__, $sql );
		}

		$pdoStatement = $this->connection->prepare( $sql );

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
	 * @return bool|mixed
	 */
	public function select( $table, Array $columns, Array $where = [ ], $order = "" )
	{
		$sqlColonsString = "`" . join( "`,`", $columns ) . "`";

		$sql = "SELECT {$sqlColonsString} FROM {$table} ";

		// If there there is an where clause.
		if( count( $where ) )
		{
			$sql .= "WHERE " . $where[ 0 ];
			$valuesWhereClause = $where[ 1 ];
		}

		// Add the order.
		$sql .= " ORDER BY " . $order;

		if( isset( $valuesWhereClause ) )
		{
			return $this->query( $sql, $valuesWhereClause );
		}

		return $this->query( $sql, [ ] );
	}

	/**
	 * Insert an data record into the connected database.
	 *
	 * @param $table
	 * @param $values
	 */
	public function insert( $table, $values )
	{
		$sql = "INSERT INTO {$table} (";

		$columns      = [ ];
		$boundValues  = [ ];
		$valuesString = "";

		foreach( $values as $columnName => $value )
		{
			$columns[]     = $columnName;
			$boundValues[] = $value;
			$valuesString .= "?,";
		}

		$sql .= "`" . join( "`,`", $columns ) . "`) VALUES ( " . rtrim( $valuesString, "," ) . ");";

		$this->query( $sql, $boundValues );
	}

	/**
	 * If the logQuerys property is set to true it will return last query that was send to the database.
	 * Otherwise it will throw an logic exception.
	 * @return mixed
	 */
	public function getLastQuery()
	{
		if( $this->logQuerys )
		{
			return $this->queryLogger->getLast();
		}
		else
		{
			throw new \LogicException( Message::getMessage( "databaseConnection.exceptions.queryNotLogged" ) );
		}
	}

	/**
	 * If the logQuerys property is set to true it will return all the query's that where send to the database.
	 * Otherwise it will throw an logic exception.
	 * @return array
	 */
	public function getAllQuerys()
	{
		if( $this->logQuerys )
		{
			return $this->queryLogger->getAll();
		}
		else
		{
			throw new \LogicException( Message::getMessage( "databaseConnection.exceptions.queryNotLogged" ) );
		}
	}

	/**
	 * This method will get information about the database in an array.
	 * @return array
	 */
	public function getDatabaseInfo()
	{
		$attributes = [
			"CLIENT_VERSION",
			"CONNECTION_STATUS",
			"DRIVER_NAME",
			"SERVER_INFO",
			"SERVER_VERSION"
		];

		$data = [ ];

		foreach( $attributes as $val )
		{
			$data[ $val ] = $this->connection->getAttribute( constant( "PDO::ATTR_$val" ) );
		}

		return $data;
	}
}
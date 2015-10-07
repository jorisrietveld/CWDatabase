<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 7-10-15 - 9:25
 * This file includes tests for all functions in the CWDatabase/DatabaseConnection class.
 */

// Require the composer autoloader and some basic config.
require( "header.php" );

// Set an handler for un caught exceptions
set_exception_handler( function ( $exception )
{
	echo "<h3>Uncaught exception!</h3>";
	var_dump( $exception );
} );

/**
 * Create CWDatabase/DatabaseConnection()
 */
if( true )
{
	/**
	 * Example database configuration.
	 */
	$config = [
		"name"      => "mysqlConnection",
		"driver"    => "mysql",
		"database"  => "test",
		"host"      => "127.0.0.1",
		"username"  => "root",
		"password"  => "toor",
		"port"      => "3306",
		"charset"   => "UTF8",
		"collation" => "utf8_general_ci",
		"strict"    => false,
		"options"   => [
			\PDO::ATTR_EMULATE_PREPARES => false
		]
	];

	/**
	 * Instantiate an new database connection object.
	 */
	$databaseConnection = new \CWDatabase\DatabaseConnection( $config );

	var_dump( $databaseConnection );
}

if( false )
{
	/**
	 * Get the database connection (PDO object)
	 * You can optionally insert new database configuration as an parameter just like in the constructor.
	 */
	$databaseConn = $databaseConnection->getConnection( $config );

	var_dump( $databaseConn );
}

if( false )
{
	/**
	 * Enable or disable the query logger, All query's to the database will be logged if set to true.
	 */
	$databaseConnection->logQuerys = true;
}
if( false )
{
	/**
	 * This will return information about the connected database in an array.
	 */
	var_dump( $databaseConnection->getDatabaseInfo() );
}
if( false )
{
	/**
	 * If the query logger is enabled, this method returns the last query send to the database otherwise
	 * It will throw an \LogicException()
	 */
	var_dump( $databaseConnection->getLastQuery() );

	/**
	 * If the query logger is enabled, this method returns all query's send to the database otherwise it
	 * will throw an \LogicException()
	 */
	var_dump( $databaseConnection->getAllQuerys() );
}
if( false )
{
	/**
	 * Get an instance of CWDatabase/Drivers/{Mysql|SqlServer|Sqlight}Driver(). If the argument $config is passed it
	 * will get an driver based on the config else it will return the current driver of the database connection.
	 */
	var_dump( $databaseConnection->getDriver() );
}
if( false )
{
	/**
	 * Perform an raw sql statement to the database. This will use the \PDO::exec() method.
	 */

	try
	{
		var_dump( $databaseConnection->rawSqlStatement( "set names `utf8`" ) );
	}
	catch( PDOException $pdoException )
	{
		echo "<h3>An pdo exception was thrown</h3>";
		var_dump( $pdoException );
		echo "<h3>database connection</h3>";
		var_dump( $databaseConnection );
	}
	catch( Exception $e )
	{
		echo "<h3>An exception was thrown</h3>";
		var_dump( $e );
	}
}
if( false )
{
	/**
	 * Perform an raw sql query to the database. This will use the \PDO::query(); method. don't use
	 * this for query's that include user input because this has no protection against SQL injections.
	 */
	try
	{
		var_dump( $databaseConnection->rawQuery( "SELECT * FROM information_schema.ENGINES;" ) );
	}
	catch( PDOException $pdoException )
	{
		echo "<h3>An pdo exception was thrown</h3>";
		var_dump( $pdoException );
		echo "<h3>database connection</h3>";
		var_dump( $databaseConnection );
	}
	catch( Exception $e )
	{
		echo "<h3>An exception was thrown</h3>";
		var_dump( $e );
	}
}
if( false )
{
	/**
	 * Perform an query to the database. This uses prepared statements with question mark placeholders or named
	 * placeholders. You can pass the sql string in the first argument and pass the values that need to be bound in
	 * second argument (witch is optional).
	 */

	try
	{
		$sqlQuestionMarks = "SELECT * FROM information_schema.ENGINES WHERE `SUPPORT` = ? AND `TRANSACTION` = ?;";
		$sqlPlaceholders  = "SELECT * FROM information_schema.ENGINES WHERE `SUPPORT` = :support AND `TRANSACTION` = :transaction;";

		$valuesQuestionMarks = [
			"YES",
			"NO"
		];

		$valuesPlaceholders = [
			":support"     => "YES",
			":transaction" => "NO"
		];

		echo "<h3>query with question mark placeholders</h3>";
		var_dump( $databaseConnection->query( $sqlQuestionMarks, $valuesQuestionMarks ) );

		echo "<h3>query with named placeholders</h3>";
		var_dump( $databaseConnection->query( $sqlPlaceholders, $valuesPlaceholders ) );
	}
	catch( Exception $e )
	{
		echo "<h3>An exception was thrown</h3>";
		var_dump( $e );
		echo "<h3>database connection</h3>";
		var_dump( $databaseConnection );
	}
}
if( true )
{
	try
	{
		/**
		 * Insert shortcut, use an associate array where the key is the field name and the value the value.
		 */
		$table = "test.users";

		$insertShortcut = [
			"name"     => "admin",
			"password" => "abc123"
		];

		var_dump( $databaseConnection->insert( $table, $insertShortcut ) );

		/**
		 * Insert normal, with the fields passed in the first argument and the values in the second argument.
		 */
		$fields = [ "name", "password" ];

		$insertNormalWithQuestionMarkPlaceholders = [
			"admin",
			"abc123"
		];

		$insertNormalWithNamedPlaceholders = [
			":name"     => "admin",
			":password" => "abc123"
		];

		echo "<h3>Normal insert with named placeholders</h3>";
		var_dump( $databaseConnection->insert( $table, $fields, $insertNormalWithNamedPlaceholders ) );

		echo "<h3>Normal insert with question mark placeholders</h3>";
		var_dump( $databaseConnection->insert( $table, $fields, $insertNormalWithQuestionMarkPlaceholders ) );


	}
	catch( PDOException $pdoException )
	{
		echo "<h3>An pdo exception was thrown</h3>";
		var_dump( $pdoException );
		echo "<h3>database connection</h3>";
		var_dump( $databaseConnection );
	}
	catch( Exception $e )
	{
		echo "<h3>An exception was thrown</h3>";
		var_dump( $e );
	}
}
if( false )
{
	try
	{

	}
	catch( PDOException $pdoException )
	{
		echo "<h3>An pdo exception was thrown</h3>";
		var_dump( $e );
		echo "<h3>database connection</h3>";
		var_dump( $databaseConnection );
	}
	catch( Exception $e )
	{
		echo "<h3>An exception was thrown</h3>";
		var_dump( $e );
	}
}
if( false )
{

}
if( false )
{

}
if( false )
{

}
if( false )
{

}




<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 7-10-15 - 9:25
 * This file includes tests for all functions in the CWDatabase/DatabaseConnection class.
 */

// Require the composer autoloader and some basic config.
require( "header.php" );

// If everything goes wrong
register_shutdown_function( function () use ( &$debugBar )
{
	$error = error_get_last();
	if( $error[ "type" ] == E_ERROR )
	{
		$debugBar[ "messages" ]->fatal( $error[ "message" ] );
	}
	\CWDatabase\Helper\DebugBar::render( $debugBar );
} );

// Set an handler for un caught exceptions
set_exception_handler( function ( $exception ) use ( &$debugbar )
{
	$debugbar[ 'exceptions' ]->addException( $exception );
} );

// Convert errors in exceptions
set_error_handler( function ( $errorNumber, $errorMessage, $errorFile, $errorLine, $context = null ) use ( &$debugBar )
{
	throw new \ErrorException( $errorMessage, 0, $errorNumber, $errorFile, $errorLine );
}, E_ALL );

// Log everything to debugbar
define( "LOG_TO_DEBUG_BAR", true );

// Master switch
define( "MASTER_SWITCH", true );

/**
 * Create CWDatabase/DatabaseConnection()
 */
if( true || MASTER_SWITCH )
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
	$databaseConnection = new \CWDatabase\DatabaseConnection( $config, $debugbar );

	echo "<h3>Connect to database</h3>";
	var_dump( $databaseConnection );
}
echo "<hr>";
if( false || MASTER_SWITCH )
{
	/**
	 * Get the database connection (PDO object)
	 * You can optionally insert new database configuration as an parameter just like in the constructor.
	 */
	$databaseConn = $databaseConnection->getConnection( $config );

	echo "<h3>Get database connection</h3>";
	var_dump( $databaseConn );
}
echo "<hr>";
if( false || MASTER_SWITCH )
{
	/**
	 * Enable or disable the query logger, All query's to the database will be logged if set to true.
	 */
	$databaseConnection->logQuerys = true;
}
echo "<hr>";
if( false || MASTER_SWITCH )
{
	/**
	 * This will return information about the connected database in an array.
	 */
	echo "<h3>Get database info</h3>";
	var_dump( $databaseConnection->getDatabaseInfo() );
}
echo "<hr>";
if( false || MASTER_SWITCH )
{
	/**
	 * If the query logger is enabled, this method returns the last query send to the database otherwise
	 * It will throw an \LogicException()
	 */
	echo "<h3>Get last query</h3>";
	var_dump( $databaseConnection->getLastQuery() );

	/**
	 * If the query logger is enabled, this method returns all query's send to the database otherwise it
	 * will throw an \LogicException()
	 */
	echo "<h3>Get all querys</h3>";
	var_dump( $databaseConnection->getAllQuerys() );
}
echo "<hr>";
if( false || MASTER_SWITCH )
{
	/**
	 * Get an instance of CWDatabase/Drivers/{Mysql|SqlServer|Sqlight}Driver(). If the argument $config is passed it
	 * will get an driver based on the config else it will return the current driver of the database connection.
	 */
	echo "<h3>Get the current driver</h3>";
	var_dump( $databaseConnection->getDriver() );
}
echo "<hr>";
if( false || MASTER_SWITCH )
{
	/**
	 * Perform an raw sql statement to the database. This will use the \PDO::exec() method.
	 */

	try
	{
		echo "<h3>Raw sql statement</h3>";
		var_dump( $databaseConnection->rawSqlStatement( "set names `utf8`" ) );
	}
	catch( PDOException $pdoException )
	{
		$debugbar[ 'exceptions' ]->addException( $pdoException );

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
echo "<hr>";
if( false || MASTER_SWITCH )
{
	/**
	 * Perform an raw sql query to the database. This will use the \PDO::query(); method. don't use
	 * this for query's that include user input because this has no protection against SQL injections.
	 */
	try
	{
		echo "<h3>Raw query</h3>";

		$pdoStatment = $databaseConnection->rawQuery( "SELECT * FROM information_schema.ENGINES;" );
		var_dump( $pdoStatment );

		echo "<h4>result</h4>";
		var_dump( $pdoStatment->fetch() );

	}
	catch( PDOException $pdoException )
	{
		$debugbar[ 'exceptions' ]->addException( $pdoException );
		echo "<h3>An pdo exception was thrown</h3>";
		var_dump( $pdoException );

		echo "<h3>database connection</h3>";
		var_dump( $databaseConnection );
	}
	catch( Exception $e )
	{
		$debugbar[ 'exceptions' ]->addException( $e );
		echo "<h3>An exception was thrown</h3>";
		var_dump( $e );
	}
}
echo "<hr>";
if( false || MASTER_SWITCH )
{
	/**
	 * Perform an query to the database. This uses prepared statements with question mark placeholders or named
	 * placeholders. You can pass the sql string in the first argument and pass the values that need to be bound in
	 * second argument (witch is optional).
	 */

	try
	{
		$sqlQuestionMarks = "SELECT * FROM information_schema.ENGINES WHERE `SUPPORT` = ? AND TRANSACTIONS = ?;";
		$sqlPlaceholders  = "SELECT * FROM information_schema.ENGINES WHERE `SUPPORT` = :support AND TRANSACTIONS = :transaction;";

		$valuesQuestionMarks = [
			"YES",
			"NO"
		];

		$valuesPlaceholders = [
			":support"     => "YES",
			":transaction" => "NO"
		];

		echo "<h3>query with question mark placeholders</h3>";
		$pdoStatment = $databaseConnection->query( $sqlQuestionMarks, $valuesQuestionMarks );
		var_dump( $pdoStatment );

		echo "<h4>result</h4>";
		var_dump( $pdoStatment->fetch() );

		echo "<h3>query with named placeholders</h3>";
		$pdoStatment = $databaseConnection->query( $sqlPlaceholders, $valuesPlaceholders );

		echo "<h4>result</h4>";
		var_dump( $pdoStatment->fetch() );

	}
	catch( Exception $e )
	{
		$debugbar[ 'exceptions' ]->addException( $e );
		echo "<h3>An exception was thrown</h3>";
		var_dump( $e );
		echo "<h3>database connection</h3>";
		var_dump( $databaseConnection );
	}
}
echo "<hr>";
if( false || MASTER_SWITCH )
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

		echo "<h3>insert shortcut</h3>";
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
		$debugbar[ 'exceptions' ]->addException( $pdoException );

		echo "<h3>An pdo exception was thrown</h3>";
		var_dump( $pdoException );
		echo "<h3>database connection</h3>";
		var_dump( $databaseConnection );
	}
	catch( Exception $e )
	{
		$debugbar[ 'exceptions' ]->addException( $e );
		echo "<h3>An exception was thrown</h3>";
		var_dump( $e );
	}
}
echo "<hr>";
if( false || MASTER_SWITCH )
{
	try
	{
		/**
		 * Delete an record from the database
		 */
		$table = "test.users";
		$id    = 1;

		echo "<h3>Delete record from {$table} where id = {$id}</h3>";
		var_dump( $databaseConnection->delete( $table, $id ) );

	}
	catch( PDOException $pdoException )
	{
		$debugbar[ 'exceptions' ]->addException( $pdoException );

		echo "<h3>An pdo exception was thrown</h3>";
		var_dump( $e );

		echo "<h3>database connection</h3>";
		var_dump( $databaseConnection );
	}
	catch( Exception $e )
	{
		$debugbar[ 'exceptions' ]->addException( $e );
		echo "<h3>An exception was thrown</h3>";
		var_dump( $e );
	}
}
echo "<hr>";
if( false || MASTER_SWITCH )
{
	try
	{
		/**
		 * Delete an record with an where clause
		 */
		$table = "test.users";

		$valuesPlaceholders  = [ ":id" => 3, ":ting" => 3 ];
		$valuesQuestionMarks = [ 2 ];

		$whereClauseNamedPlaceholders        = [ "id = :id AND id = :ting", $valuesPlaceholders ];
		$whereClauseQuestionMarkPlaceholders = [ "id = ?", $valuesQuestionMarks ];

		echo "<h3>DeleteWhere question mark placeholders</h3>";
		var_dump( $databaseConnection->deleteWhere( $table, $whereClauseQuestionMarkPlaceholders ) );

		echo "<h3>DeleteWhere named placeholders</h3>";
		var_dump( $databaseConnection->deleteWhere( $table, $whereClauseNamedPlaceholders ) );

	}
	catch( PDOException $pdoException )
	{
		$debugbar[ 'exceptions' ]->addException( $pdoException );

		echo "<h3>An pdo exception was thrown</h3>";
		var_dump( $pdoException );

		echo "<h3>database connection</h3>";
		var_dump( $databaseConnection );
	}
	catch( Exception $e )
	{
		$debugbar[ 'exceptions' ]->addException( $e );
		echo "<h3>An exception was thrown</h3>";
		var_dump( $e );
	}
}
echo "<hr>";
if( false || MASTER_SWITCH )
{
	try
	{
		echo "<h3>Update shortcut (table, set, id)</h3>";
		$table = "test.users";

		// update where id
		$setShortcut = [ "name" => "newName" ];
		$id          = 7;
		$whereClause = [ "id = :id", [ ":id" => 1 ] ];

		var_dump( $databaseConnection->update( $table, $setShortcut, $id ) );
	}
	catch( PDOException $pdoException )
	{
		$debugbar[ 'exceptions' ]->addException( $pdoException );

		echo "<h3>An pdo exception was thrown</h3>";
		var_dump( $pdoException );
	}
	catch( Exception $e )
	{
		$debugbar[ 'exceptions' ]->addException( $e );
		echo "<h3>An exception was thrown</h3>";
		var_dump( $e );
	}
}
if( false || MASTER_SWITCH )
{
	try
	{
		echo "<h3>Update statement ( table, set, where clause )</h3>";
		$table = "test.users";

		$setShortcut = [ "name" => "newName", "password" => "newPassword" ];
		$whereClause = [ "`id` = ? ", [ 1 ] ];

		var_dump( $databaseConnection->update( $table, $setShortcut, $whereClause ) );
	}
	catch( PDOException $pdoException )
	{
		$debugbar[ 'exceptions' ]->addException( $pdoException );

		echo "<h3>An pdo exception was thrown</h3>";
		var_dump( $pdoException );
	}
	catch( Exception $e )
	{
		$debugbar[ 'exceptions' ]->addException( $e );
		echo "<h3>An exception was thrown</h3>";
		var_dump( $e );
	}
}
if( false || MASTER_SWITCH )
{
	try
	{
		echo "<h3>Select statement</h3>";
		$table = "test.users";

		$fields = [ "username", "email", "password", "active" ];


	}
	catch( PDOException $pdoException )
	{
		$debugbar[ 'exceptions' ]->addException( $pdoException );

		echo "<h3>An pdo exception was thrown</h3>";
		var_dump( $pdoException );
	}
	catch( Exception $e )
	{
		$debugbar[ 'exceptions' ]->addException( $e );
		echo "<h3>An exception was thrown</h3>";
		var_dump( $e );
	}
}
echo "<hr>";
echo "<hr>";
echo "<hr>";

$debugBar = $databaseConnection->getDebugBar();

\CWDatabase\Helper\DebugBar::render( $debugBar );

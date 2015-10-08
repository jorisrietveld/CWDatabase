<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 8-10-15 - 14:55
 * ----------------------------------------------------------
 *     \CWDatabase::DatabaseConnection Usage examples
 * ---------------------------------------------------------
 */

/**
 * A database configuration example.
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

/**
 * Get the database connection (PDO object)
 * You can optionally insert new database configuration as an parameter just like in the constructor.
 */
$databaseConn = $databaseConnection->getConnection( $config );

/**
 * Enable or disable the query logger, All query's to the database will be logged if set to true.
 */
$databaseConnection->logQuerys = true;

/**
 * This will return information about the connected database in an array.
 */
$databaseConnection->getDatabaseInfo();

/**
 * If the query logger is enabled, this method returns the last query send to the database otherwise
 * It will throw an \LogicException()
 */
$databaseConnection->getLastQuery();

/**
 * If the query logger is enabled, this method returns all query's send to the database otherwise it
 * will throw an \LogicException()
 */
$databaseConnection->getAllQuerys();

/**
 * Get an instance of CWDatabase/Drivers/{Mysql|SqlServer|Sqlight}Driver(). If the argument $config is passed it
 * will get an driver based on the config else it will return the current driver of the database connection.
 */
$databaseConnection->getDriver();

/**
 * Perform an raw sql statement to the database. This will use the \PDO::exec() method.
 */

$databaseConnection->rawSqlStatement( "set names `utf8`" );

/**
 * Perform an raw sql query to the database. This will use the \PDO::query(); method. don't use
 * this for query's that include user input because this has no protection against SQL injections.
 */

$pdoStatement = $databaseConnection->rawQuery( "SELECT * FROM information_schema.ENGINES;" );

$dataSet = $pdoStatement->fetch();

/**
 * Perform an query to the database. This uses prepared statements with question mark placeholders or named
 * placeholders. You can pass the sql string in the first argument and pass the values that need to be bound in
 * second argument (witch is optional).
 */

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

// Prepared statement with question mark placeholders
$pdoStatement = $databaseConnection->query( $sqlQuestionMarks, $valuesQuestionMarks );
$dataSet      = $pdoStatement->fetchAll();

// Prepared statement with named placeholders
$pdoStatement = $databaseConnection->query( $sqlPlaceholders, $valuesPlaceholders );
$dataSet      = $pdoStatement->fetchAll();

/**
 * Prepared insert statement, use an associate array where the key is the field name and the value the value.
 */
$table = "test.users";

$insertShortcut = [
	"name"     => "admin",
	"password" => "abc123"
];

$affectedRows = $databaseConnection->insert( $table, $insertShortcut );

/**
 * Prepared insert statement, the values are passed in the third argument as an array, each value will be replaced by
 * an question mark placeholder where the values will later be bound to.
 */
$fields = [ "name", "password" ];

$insertNormalWithQuestionMarkPlaceholders = [
	"admin",
	"abc123"
];

$affectedRows = $databaseConnection->insert( $table, $fields, $insertNormalWithQuestionMarkPlaceholders );

/**
 * Prepared insert statement, the values are passed in the third argument as an associate array where the keys will
 * be used as named placeholders where the values will be bound to.
 */

$insertNormalWithNamedPlaceholders = [
	":name"     => "admin",
	":password" => "abc123"
];

$affectedRows = $databaseConnection->insert( $table, $fields, $insertNormalWithNamedPlaceholders );

/**
 * Prepared delete statement, delete an record from $table where id = $id
 */
$table = "test.users";
$id    = 1;

$affectedRows = $databaseConnection->delete( $table, $id );

/**
 * Prepared delete statement, delete an record from $table with an where clause.
 */
$table = "test.users";

$valuesPlaceholders           = [ ":id" => 3, ":ting" => 3 ];
$whereClauseNamedPlaceholders = [ "id = :id AND id = :ting", $valuesPlaceholders ];

$affectedRows = $databaseConnection->deleteWhere( $table, $whereClauseNamedPlaceholders );

/**
 * Prepared delete statement, delete an record from $table with an where clause.
 */
$valuesQuestionMarks                 = [ 2 ];
$whereClauseQuestionMarkPlaceholders = [ "id = ?", $valuesQuestionMarks ];

$affectedRows = $databaseConnection->deleteWhere( $table, $whereClauseQuestionMarkPlaceholders );

/**
 * Prepared update statement, The set short cut must be an associative array. the key is the database field and the
 * value the value that the field will be set to.
 */

$table = "test.users";
$set   = [ "name" => "ting" ];
$id    = 7;

$affectedRows = $databaseConnection->update( $table, $setShortcut, $id );

/**
 * Prepared update statement, The where must be an array. the first element of the array is the where clause with
 * either named or question mark placeholders. And the second parameter will be an array that holds the values that
 * will be bound to the placeholders.
 */

$table = "test.users";
$set   = [ "name" => "ting" ];

// With named placeholders
$valuesNamedPlaceholders      = [ ":id" => 3 ];
$whereClauseNamedPlaceholders = [ "id = :id", $valuesNamedPlaceholders ];

$affectedRows = $databaseConnection->update( $table, $setShortcut, $whereClauseNamedPlaceholders );

// With question mark placeholders
$valuesQuestionMarkPlaceholders      = [ 3 ];
$whereClauseQuestionMarkPlaceholders = [ "id = ?", $valuesPlaceholders ];

$affectedRows = $databaseConnection->update( $table, $setShortcut, $whereClauseNamedPlaceholders );

/**
 * Prepared select statement, The where must be an array. the first element of the array is the where clause with
 * either named or question mark placeholders. And the second parameter will be an array that holds the values that
 * will be bound to the placeholders.
 */
$table  = "test.users";
$fields = [ "name", "password" ];

$whereClauseNamedPlaceholders        = [ "id = :id ", [ 1 ] ];
$whereClauseQuestionMarkPlaceholders = [ "id = ? ", [ 1 ] ];

// With named placeholders
$pdoStatement = $databaseConnection->select( $table, $fields, $whereClauseNamedPlaceholders );
$dataSet      = $pdoStatement->fetchAll();

// With question mark placeholders
$pdoStatement = $databaseConnection->select( $table, $fields, $whereClauseQuestionMarkPlaceholders );
$dataSet      = $pdoStatement->fetchAll();



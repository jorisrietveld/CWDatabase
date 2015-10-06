<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 6-10-15 - 13:53
 */

define( "WEBSERVER_ROOT_PATH", "/var/www/" );

require( "header.php" );
require( "databaseConfigExample.php" );

$config = $connections[ "mysqlConnection" ];

$database = new \CWDatabase\DatabaseConnection( $config );

$sql = "SELECT * FROM CampuswerkSite.news WHERE id = :id ";

$values = [ ":id" => 1 ];

$database->query( $sql, $values );

var_dump( $database->getAllQuerys() );
var_dump( $database->getLastQuery() );

try
{
	$database->select( [ "id", "title" ], "CampuswerkSite.news", [ "id = :id", [ 1 ] ] );
}
catch( PDOException $e )
{
	var_dump( $e );
}
finally
{
	var_dump( $database->getAllQuerys() );
	var_dump( $database->getLastQuery() );
}

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

/*$sql = "SELECT * FROM CampuswerkSite.news WHERE id = :id ";

$values = [ ":id" => 1 ];

$database->query( $sql, $values );

var_dump( $database->getAllQuerys() );
var_dump( $database->getLastQuery() );*/

try
{
	$table         = "CampuswerkSite.news";
	$selectColumns = [ "id", "title" ];
	$whereClause   = [ "id = :id", [ ":id" => 1 ] ];
	$orderBy       = "id DESC";

	echo "<h1>SELECT QUERY</h1>";

	$selectDataSet = $database->select( $table, $selectColumns, $whereClause, $orderBy );
	var_dump( $selectDataSet->fetchAll() );

	echo "<h1>SELECT SERVER DATA</h1>";

	var_dump( $database->getDatabaseInfo() );
}
catch( PDOException $e )
{
	var_dump( $e );
}
finally
{
	echo "<h1>finnaly</h1>";
	var_dump( $database->getAllQuerys() );
	var_dump( $database->getLastQuery() );
}
echo "<hr>";
try
{
	$table = "CampuswerkSite.news";

	$insertColumns = [ "title", "article", "image", "order", "active", "date" ];

	$insertValues = [
		":title"   => "newArticle",
		":article" => "article",
		":image"   => "image",
		":order"   => 1,
		":active"  => 1,
		":date"    => ( new \CWDatabase\Helper\DatabaseLiteral( "NOW()" ) )
	];

	echo "<h1>insert data</h1>";
	var_dump( $database->insert( $table, $insertColumns, $insertValues ) );



}
catch( PDOException $e )
{
	var_dump( $e );
}
finally
{
	echo "<h1>finnaly</h1>";
	var_dump( $database->getAllQuerys() );
	var_dump( $database->getLastQuery() );
}

echo "<hr>";
<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 7-10-15 - 9:10
 */
define( "WEBSERVER_ROOT_PATH", "/var/www/" );

require( "header.php" );
require( "databaseConfigExample.php" );

$config = $connections[ "mysqlConnection" ];

try
{
	$database = new \CWDatabase\DatabaseConnection( $config );

	$table        = "CampuswerkSite.news";
	$insertValues = [
		"title"   => "newArticle",
		"article" => "article",
		"image"   => "image",
		"order"   => 1,
		"active"  => 1,
		"date"    => ( new \CWDatabase\Helper\DatabaseLiteral( "NOW()" ) )
	];

	echo "<h3>insert data</h3>";

	var_dump( $database->insert( $table, $insertValues ) );

	$database->query();

}
catch( PDOException $pdoException )
{
	echo "<h3>An PDOException was thrown</h3>";
	var_dump( $pdoException );
}
catch( Exception $e )
{
	echo "<h3>An Exception was thrown</h3>";
}
finally
{
	echo "<h3>Finnaly</h3>";
	var_dump( $database );
}
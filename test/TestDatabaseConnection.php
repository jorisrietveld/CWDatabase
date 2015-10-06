<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 21-9-15 - 9:20
 * Licence: GPLv3
 */

require( "header.php" );
require( "databaseConfigExample.php" );

$conConf = $connections[ "mysqlConnection" ];

$databaseConnection = new \CWDatabase\DatabaseConnection( $conConf );

var_dump( $databaseConnection );

$connection = $databaseConnection->getConnection( $conConf );

var_dump( $connection );

$stmt = $connection->query( "SHOW DATABASES" );

$queryResult = $stmt->fetchall( \PDO::FETCH_ASSOC );

$sqlUseStatement = "USE `information_schema`;";
var_dump( $sqlUseStatement );

$connection->exec( $sqlUseStatement );

$stmt = $connection->query( "SELECT * FROM `CHARACTER_SETS`;" );
$queryResult = $stmt->fetchAll( \PDO::FETCH_ASSOC );

var_dump( $stmt );
var_dump( $queryResult );
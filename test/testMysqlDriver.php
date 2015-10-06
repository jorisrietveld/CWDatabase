<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 4-9-15 - 15:18
 * Licence: GPLv3
 */

include "header.php";
include "databaseConfigExample.php";

try
{
	$sql = "SELECT * FROM `users`";

	$driverFactory = new \CWDatabase\Drivers\DriverFactory();

	///////////////////////////////////////////////////////////////////////////// test case 1
	echo "<hr><h3>test simple config - {$sql}</h3>";

	$testebleConfig = $connections[ "simpleMysqlConnection" ];

	$mysqlDriver = $driverFactory->createDriver( $testebleConfig );

	$pdo = $mysqlDriver->connect( $testebleConfig );

	$stmt = $pdo->prepare( $sql );

	$stmt->execute();

	$result = $stmt->fetchAll( PDO::FETCH_ASSOC );

	dump( $testebleConfig );

	dump( $result );
}
catch( Exception $e )
{
	dump( $e );
}
try
{
	///////////////////////////////////////////////////////////////////////////// test case 2
	echo "<hr><h3>test advanced config - {$sql}</h3>";

	$advancedConfig = $connections[ "advancedMysqlConnection" ];

	$mysqlDriver1 = $driverFactory->createDriver( $advancedConfig );

	$pdo1 = $mysqlDriver1->connect( $advancedConfig );

	$stmt = $pdo1->prepare( $sql );

	$stmt->execute();

	$result = $stmt->fetchAll( PDO::FETCH_ASSOC );

	dump( $advancedConfig );

	dump( $result );
	///////////////////////////////////////////////////////////////////////////// test case 3
	echo "<hr><h3>test set attributes in advanced config</h3>";

	$attributes = [
		"AUTOCOMMIT",
		"ERRMODE",
		"CASE",
		"CLIENT_VERSION",
		"CONNECTION_STATUS",
		"ORACLE_NULLS",
		"DRIVER_NAME",
		"SERVER_INFO",
		"SERVER_VERSION"
	];

	foreach( $attributes as $val )
	{
		echo "PDO::ATTR_$val: ";
		echo $pdo1->getAttribute( constant( "PDO::ATTR_$val" ) ) . "<br>";
	}
}
catch( Exception $e )
{
	dump( $e );
}
///////////////////////////////////////////////////////////////////////////// test case 4
try
{
	echo "<hr><h3>Test config with unix socket:</h3>";
	$socketConfig = $connections[ "mysqlConnectionWithSocket" ];
	dump( $socketConfig );

	$pdo2 = $driverFactory->createDriver( $socketConfig );

	$pdo2 = $pdo2->connect( $socketConfig );

	$stmt = $pdo2->prepare( $sql );

	$stmt->execute();

	$result = $stmt->fetchAll( PDO::FETCH_ASSOC );

	dump( $result );

	///////////////////////////////////////////////////////////////////////////// test case 5

	///////////////////////////////////////////////////////////////////////////// test case 6

	///////////////////////////////////////////////////////////////////////////// test case 7

	///////////////////////////////////////////////////////////////////////////// test case 8

}
catch( Exception $e )
{
	dump( $e );
}
<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 2-9-15 - 15:49
 * Licence: GPLv3
 */

include "header.php";

$driver = new \CWDatabase\Drivers\Driver();

$connections = [
	"mysqlConnection" => [
		"name"     => "mysqlConnection",
		"driver"   => "mysql",
		"host"     => "127.0.0.1",
		"dbname"   => "test",
		"username" => "root",
		"password" => "toor",
		"port"     => "3306",
		"options"  => [
			\PDO::ATTR_EMULATE_PREPARES => true
		]
	]
];

$conn1 = $connections[ "mysqlConnection" ];
$dsn0  = "mysql:host={$conn1["host"]};port={$conn1["port"]};dbname={$conn1["dbname"]}";
$dsn1  = "mysql:host={$conn1["host"]};port={$conn1["port"]}";

dump( $driver->getDefaultOptions() );
dump( $driver->getOptions( $conn1 ) );
$driver->setDefaultOptions( $conn1[ "options" ] );

dump( $driver->openConnection( $dsn1, $conn1, $driver->getOptions( $conn1[ "options" ] ) ), 1 );
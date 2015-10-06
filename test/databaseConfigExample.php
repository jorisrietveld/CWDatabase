<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 4-9-15 - 15:20
 * Licence: GPLv3
 */

if( !function_exists( "dump" ) )
{
	include "header.php";
}

$connections = [
	"simpleMysqlConnection"     => [
		"name"     => "simpleMysqlConnection",
		"driver"   => "mysql",
		"database" => "test",
		"host"     => "127.0.0.1",
		"username" => "root",
		"password" => "toor"
	],
	"advancedMysqlConnection"   => [
		"name"      => "mysqlConnection",
		"driver"    => "mysql",
		"host"      => "127.0.0.1",
		"database"  => "test",
		"username"  => "root",
		"password"  => "toor",
		"port"      => "3306",
		"charset"   => "utf8",
		"collation" => "utf8_general_ci",
		"strict"    => true
	],
	"mysqlConnection"           => [
		"name"      => "mysqlConnection",
		"driver"    => "mysql",
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
	],
	"mysqlConnectionWithSocket" => [
		"name"        => "mysqlConnection",
		"driver"      => "mysql",
		"unix_socket" => "/var/run/mysqld/mysqld.sock",
		"username"    => "root",
		"password"    => "toor",
		"database"    => "test",
		"port"        => "3306",
		"charset"     => "utf8",
		"collation"   => "utf8_general_ci"
	]
];

if( isset( $_GET[ "watch" ] ) )
{
	dump( $connections, true );
}
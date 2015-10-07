<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 2-9-15 - 15:40
 * Licence: GPLv3
 */

define( "PROJECT_NAME", "CWDatabase" );
define( "PROJECT_ROOT", str_replace( "test", "", __DIR__ ) );

define( "PROJECT_SRC", PROJECT_ROOT . "src" . DIRECTORY_SEPARATOR );
define( "PROJECT_FILE_EXT", ".php" );

// Register the composer autoloader
require( "../vendor/autoload.php" );

// Register the project autoloader.
spl_autoload_register( function ( $class )
{
	// Resolve the path to the class with actual system paths.
	$classLocation = PROJECT_SRC . str_replace( "\\", DIRECTORY_SEPARATOR, $class ) . PROJECT_FILE_EXT;

	if( file_exists( $classLocation ) )
	{
		include $classLocation;
	}

} );

function dump( $item, $showVarTypes = false )
{
	echo "<pre>";
	( $showVarTypes ) ? var_dump( $item ) : print_r( $item );
	echo "</pre>";
}

if( !function_exists( "thisIsTheIndex" ) )
{
	echo <<<HTML
	<!DOCTYPE html>
	<style>
	.illuminatiIsEveryWhere{
		background-color:dodgerblue;
		color:white;font-weight:bold;
		font-family: ubuntu;
		border-radius:10px;
		height:30px;
		width:200px;
		border:groove blue;
		text-align: center;
		vertical-align: middle;
	};
	</style>
	<script>
	function historyMinusOne()
	{
        window.location = "index.php";
	}
	</script>
HTML;
	echo "<button class='illuminatiIsEveryWhere' onclick='historyMinusOne()'><- Go back</button>";
}

use \DebugBar\StandardDebugBar;


$debugbar = new StandardDebugBar();

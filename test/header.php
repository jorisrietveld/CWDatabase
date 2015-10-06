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

/**
 * Debug levels are
 */
define( "PROJECT_DEBUG_LEVEL", 1 );

spl_autoload_register( function ( $class )
{
	// Resolve the path to the class with actual system paths.
	$classLocation = PROJECT_SRC . str_replace( "\\", DIRECTORY_SEPARATOR, $class ) . PROJECT_FILE_EXT;
	include $classLocation;
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

/*set_error_handler(function( $errorCode, $errorMessage ){
	echo "<table style='margin-bottom: 20px;' border='1px solid black'><thead style='background-color: coral'><th>Code:</th><th>Message:</th></thead><tbody><tr><td>{$errorCode}</td><td>{$errorMessage}</td></tr></tbody></table>";
});

set_exception_handler(function( $exception ){
	echo "<table border='1px solid black'><thead style='background-color: coral'><th>Uncaught exception!</th></thead><tbody><tr><td>{$exception}</td></tr></tbody></table>";
});*/
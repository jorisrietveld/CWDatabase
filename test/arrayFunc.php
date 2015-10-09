<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 8-10-15 - 23:26
 */

require( "header.php" );

$array = [ "name", "password", "age" ];

/**
 *
 */
$array = [ "name", "password", "age" ];

$result = rtrim( join( ", ", array_map( function ( $array )
{
	return "`" . $array . "` = ?";
}, $array ) ), "`", "' " );

var_dump( $result );
var_dump( $array );

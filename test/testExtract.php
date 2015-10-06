<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 4-9-15 - 17:59
 * Licence: GPLv3
 */

require( "header.php" );

$array = [
	"varname1" => "varvalue1"
];

extract( $array );

dump( $varname1 );

extract( $array, EXTR_PREFIX_ALL, "prefix");

dump( $prefix_varname1 );

$var____boom = "boom";

echo $var____boom;


<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 8-10-15 - 23:26
 */

require( "header.php" );

$array = array_keys( [ "name" => "nName", "password" => "nName", "age" => "nName" ] );

$sqlSetString = array_map( function ( $field )
{
	return "`" . $field . "` = ?";
}, $array );

var_dump( $sqlSetString );

echo "<h3>Test CWDatabase/helper/Arr:: </h3>";

$numArray   = range( 0, 20 );
$assocArray = [ ];

foreach( $numArray as $key => $value )
{
	$assocArray[ "blabla" . $value ] = $value;
}

echo "<h3>numeric array</h3>";
var_dump( $numArray );

echo "<h3>associate array</h3>";
var_dump( $assocArray );

echo "<h3>numeric array arr::isAssoc()</h3>";
var_dump( \CWDatabase\Helper\Arr::isAssoc( $numArray ) );
echo "<h3>numeric array arr::isNumeric()</h3>";
var_dump( \CWDatabase\Helper\Arr::isNumeric( $numArray ) );

echo "<h3>assoc array arr::isAssoc()</h3>";
var_dump( \CWDatabase\Helper\Arr::isAssoc( $assocArray ) );
echo "<h3>assoc array arr::isNumeric()</h3>";
var_dump( \CWDatabase\Helper\Arr::isNumeric( $assocArray ) );
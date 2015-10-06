<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 2-9-15 - 16:19
 * Licence: GPLv3
 */
function thisIsTheIndex()
{
	return "yes!";
}

include( "header.php" );

$files = scandir( __DIR__ . DIRECTORY_SEPARATOR );
$files = array_slice( $files, 2 );

echo "<!DOCTYPE html><html><style>*{margin: 0; padding: 0;}</style><head></head><body>";
echo "<center>";
echo "<div style=\"background-color: dodgerblue;\">";
echo "<h1 style=\"padding:20px;\">welcome on the CWDatabase test pages</h1>";
echo "<hr>";
echo "</div>";

echo "<h3>available test pages:</h3>";
foreach( $files as $file )
{
	echo ($file=="index.php"||$file=="header.php"||$file=="databaseConfigExample.php")?($file == "databaseConfigExample.php")?"<a href=\"{$file}?watch=true\">{$file}</a><br>":"":"<a href=\"{$file}\">{$file}</a><br>";

}
echo "</center></body></html>";

echo <<<HTML
<style>
#footer{
	position: fixed;
	width: 100%;
	height: 30px;
	text-align: center;
	color: #ffffff;
	background-color: darkgrey;
	bottom: 0;
}
</style>
<div id="footer">
	<a href="http://local.phpdoc">Go to php documentation</a>
</div>
HTML;

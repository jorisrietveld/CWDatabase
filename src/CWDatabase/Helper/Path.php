<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 10-6-15 - 19:13
 */

namespace CWDatabase\Helper;

class Path
{
	// TODO write code that checks whether an directory exists.
	public static function directoryExists( $path )
	{

	}

	// TODO write code that checks whether an file exists.
	public static function fileExists( $path )
	{

	}

	/**
	 * This method WIll clean system paths. so rootDir/firstDir/secondDir/../thirdDir/file becomes
	 * /rootDir/thirdDir/file
	 *
	 * @param $path
	 *
	 * @return string
	 */
	public static function clean( $path )
	{
		$path      = str_replace( [ '/', '\\' ], DIRECTORY_SEPARATOR, $path );
		$parts     = array_filter( explode( DIRECTORY_SEPARATOR, $path ), 'strlen' );
		$absolutes = [ ];

		foreach( $parts as $part )
		{
			if( '.' == $part )
			{
				continue;
			}
			if( '..' == $part )
			{
				array_pop( $absolutes );
			}
			else
			{
				$absolutes[] = $part;
			}
		}

		return implode( DIRECTORY_SEPARATOR, $absolutes );
	}

	public static function normalizePath( $path )
	{
		// Array to build a new path from the good parts
		$parts = [ ];

		// Replace backslashes with forwardslashes.
		$path = str_replace( '\\', '/', $path );

		// Combine multiple slashes into a single slash
		$path = preg_replace( '/\/+/', '/', $path );

		// Collect path segments
		$segments = explode( '/', $path );

		// Initialize testing variable
		$test = '';
		foreach( $segments as $segment )
		{
			if( $segment != '.' )
			{
				$test = array_pop( $parts );
				if( is_null( $test ) )
				{
					$parts[] = $segment;
				}
				elseif( $segment == '..' )
				{
					if( $test == '..' )
					{
						$parts[] = $test;
					}

					if( $test == '..' || $test == '' )
					{
						$parts[] = $segment;
					}
				}
				else
				{
					$parts[] = $test;
					$parts[] = $segment;
				}
			}
		}

		return implode( '/', $parts );
	}
}
<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 1-10-15 - 20:23
 */

namespace CWDatabase\Helper;

class Str
{
	/**
	 * Replace all $search items to $replace
	 * @param $string
	 * @param $search
	 * @param $replace
	 *
	 * @return mixed
	 */
	public static function replaceAll( $string, $search, $replace )
	{
		return str_replace( $search, $replace, $string );
	}

	/**
	 * Replace items by $search to $replace[]
	 *
	 * @param       $string
	 * @param       $search
	 * @param array $replace
	 *
	 * @return mixed
	 */
	public static function replaceArray( $string, $search, array $replace )
	{
		foreach ($replace as $value)
		{
			$string = preg_replace('/'.$search.'/', $value, $string, 1);
		}

		return $string;
	}

	/**
	 * Return the real length of an string.
	 * @param            $string
	 * @param bool|false $charset
	 *
	 * @return int
	 */
	public static function length( $string, $charset = false )
	{
		if( $charset )
		{
			return mb_strlen( $string );
		}
		else
		{
			return mb_strlen( $string, $charset );
		}
	}
}
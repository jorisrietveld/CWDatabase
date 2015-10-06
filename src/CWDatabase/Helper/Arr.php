<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 24-9-15 - 23:31
 * Licence: GPLv3
 */

namespace CWSite\Helper;

class Arr
{
	/**
	 * Checks whether an array is an associative
	 * @param $array
	 *
	 * @return bool
	 */
	public static function isAssoc( $array )
	{
		return array_keys( $array ) !== range( 0, count( $array ) - 1 );
	}

	/**
	 * Get an value from an array by key using dot notation.
	 *
	 * @param $array
	 * @param $key
	 *
	 * @return array
	 */
	public static function get( $array, $key )
	{
		// key = root.inner.value
		if( $key === null )
		{
			return [ ];
		}

		if( isset( $array[ $key ] ) )
		{
			return $array[ $key ];
		}

		foreach( explode( ".", $key ) as $keySegment )
		{
			if( !( is_array( $array ) ) || !( array_key_exists( $keySegment, $array ) ) )
			{
				false;
			}

			$array = $array[ $keySegment ];
		}

		return $array;
	}

	/**
	 * Set an value to array using the dot notation.
	 *
	 * @param $array
	 * @param $key
	 * @param $value
	 *
	 * @return mixed
	 */
	public static function set( &$array, $key, $value )
	{
		if( is_null( $key ) )
		{
			return $array = $value;
		}

		$keys = explode( '.', $key );

		while( count( $keys ) > 1 )
		{
			$key = array_shift( $keys );

			if( !isset( $array[ $key ] ) || !is_array( $array[ $key ] ) )
			{
				$array[ $key ] = [ ];
			}

			$array =& $array[ $key ];
		}

		$array[ array_shift( $keys ) ] = $value;

		return $array;
	}

	/**
	 * Check if an array has the key using dot notation.
	 *
	 * @param $array
	 * @param $key
	 *
	 * @return bool
	 */
	public static function has( $array, $key )
	{
		if( empty( $array ) || is_null( $key ) )
		{
			return false;
		}

		if( array_key_exists( $key, $array ) )
		{
			return true;
		}

		foreach( explode( '.', $key ) as $segment )
		{
			if( !is_array( $array ) || !array_key_exists( $segment, $array ) )
			{
				return false;
			}

			$array = $array[ $segment ];
		}

		return true;
	}

	/**
	 * Convert an object to an array.
	 * @param $object
	 *
	 * @return array
	 */
	public static function objectToArray( $object )
	{
		$array = [ ];

		foreach( $object as $key => $val )
		{
			$array[ $key ] = (array)$val;

			foreach( $val as $key1 => $val1 )
			{
				$array[$key][$key1] = (array)$val1;
			}
		}
		return $array;
	}
}
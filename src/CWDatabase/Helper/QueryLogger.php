<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 5-10-15 - 15:15
 */

namespace CWDatabase\Helper;

class QueryLogger
{
	private $querrys = [ ];

	public function log( $method, $sql )
	{
		$this->querrys[] = [ $method, $sql ];
	}

	public function getLast()
	{
		return end( $this->querrys );
	}

	public function getAll()
	{
		return $this->querrys;
	}

}
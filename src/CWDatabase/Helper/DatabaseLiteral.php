<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 7-10-15 - 8:44
 */

namespace CWDatabase\Helper;

class DatabaseLiteral
{
	protected $literal;

	public function __construct( $literal )
	{
		$this->literal = $literal;
	}

	public function __toString()
	{
		return $this->literal;
	}

}
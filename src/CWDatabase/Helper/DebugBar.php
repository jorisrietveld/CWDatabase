<?php
/**
 * Author: Joris Rietveld <jorisrietveld@protonmail.com>
 * Date: 7-10-15 - 15:38
 */

namespace CWDatabase\Helper;

class DebugBar
{
	public static function render( $debugBar )
	{
		$debugBarRender = $debugBar->getJavascriptRenderer();
		$debugBarRender->getAsseticCollection();

		list( $cssCollection, $jsCollection ) = $debugBarRender->getAsseticCollection();

		$css          = $cssCollection->dump();
		$javascript   = $jsCollection->dump();
		$debugBarBody = $debugBarRender->render();

		echo "<style>{$css}</style><script>{$javascript}</script>{$debugBarBody}";
	}
}
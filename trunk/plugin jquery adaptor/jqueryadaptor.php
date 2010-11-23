<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Import library dependencies
jimport('joomla.event.plugin');

class plgSystemjQueryAdaptor extends JPlugin
{
	function onAfterDispatch()
	{
		$document =& JFactory::getDocument();
		
		$version = $this->params->get( 'version', '132' );
		$compatmode = $this->params->get( 'compatmode', '1' );
		
		$version = (int) $version;
		$compatmode = (int) $compatmode;
		
		switch ($version)
		{
			case '132':
				$document->addScript( JURI::base() . 'plugins/system/jqueryadaptor/jquery-1.3.2.min.js' );
				break;
			case '142':
				$document->addScript( JURI::base() . 'plugins/system/jqueryadaptor/jquery-1.4.2.min.js' );
				break;
		}
		
		if ($compatmode == 1)
		{
			$code = 'if (typeof jQuery !== "undefined" && $ == jQuery) { $.noConflict(); }'."\n";
			$document->addScriptDeclaration( $code );
		}

		return true;
	}
}
?>
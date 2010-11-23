<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

class FacetedVideosViewNavigation extends JView
{
    function display($tpl = null)
    {
		$document =& JFactory::getDocument();
		$document->setTitle( JText::_( 'Faceted Videos - Navigation' ) );
		$document->addStyleSheet( '/components/com_facetedvideos/views/navigation/tmpl/default.css' );
		
		$params =& JComponentHelper::getParams( 'com_facetedvideos' );

		$showExtraColumns = $params->get( 'showcustomcolumns', 0 );
		$showSelectedFacets = $params->get( 'showselectedfacets', 0 );
		
		$this->assignRef( 'showextracolumns', $showExtraColumns );
		$this->assignRef( 'showselectedfacets', $showSelectedFacets );
		
		parent::display($tpl);
    }
}
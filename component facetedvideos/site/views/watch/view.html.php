<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');

class FacetedVideosViewWatch extends JView
{
    function display($tpl = null)
    {
		$document =& JFactory::getDocument();
		$document->setTitle( JText::_( 'Faceted Videos - Watch Video' ) );
		$document->addStyleSheet( '/components/com_facetedvideos/views/watch/tmpl/default.css' );
		
		$params =& JComponentHelper::getParams( 'com_facetedvideos' );
		
		$vWidth = $params->get( 'width', 320 );
		$vHeight = $params->get( 'height', 240 );
		$videoDir = $params->get( 'videodir', 'com_facetedvideos' );
		
		$this->assignRef( 'videowidth', $vWidth );
		$this->assignRef( 'videoheight', $vHeight );
		$this->assignRef( 'videodir', $videoDir );
		
		parent::display($tpl);
    }
}
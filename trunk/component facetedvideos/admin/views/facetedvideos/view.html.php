<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view' );
 
class FacetedVideosViewFacetedVideos extends JView
{
    /**
     *
     * @return void
     **/
    function display($tpl = null)
    {
        JToolBarHelper::title( JText::_( 'Control Panel' ), 'generic.png' );
		JToolBarHelper::preferences( 'com_facetedvideos' );
 
        parent::display($tpl);
    }
}
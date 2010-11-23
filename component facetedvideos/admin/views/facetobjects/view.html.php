<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view' );
 
class FacetedVideosViewFacetObjects extends JView
{
    /**
     *
     * @return void
     **/
    function display($tpl = null)
    {
        JToolBarHelper::title( JText::_( 'Facets' ), 'generic.png' );
        JToolBarHelper::addNew();
        JToolBarHelper::editList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
        JToolBarHelper::deleteList( JText::_( 'Are you sure to delete the selected items?' ) );
		
        parent::display($tpl);
    }
}
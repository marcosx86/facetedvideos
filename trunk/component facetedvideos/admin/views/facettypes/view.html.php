<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view' );
 
class FacetedVideosViewFacetTypes extends JView
{
    function display($tpl = null)
    {
        JToolBarHelper::title( JText::_( 'Facet Types' ), 'generic.png' );
        JToolBarHelper::addNew();
        JToolBarHelper::editList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
        JToolBarHelper::deleteList( JText::_( 'Are you sure to delete the selected items? Note that items with subitems cannot be removed until these ones!' ) );

        parent::display($tpl);
    }
}
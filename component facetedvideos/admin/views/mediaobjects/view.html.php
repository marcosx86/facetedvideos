<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view' );
 
class FacetedVideosViewMediaObjects extends JView
{
    function display($tpl = null)
    {
        JToolBarHelper::title( JText::_( 'Videos' ), 'generic.png' );
        JToolBarHelper::addNew();
        JToolBarHelper::editList();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::deleteList( JText::_( 'Are you sure to delete the selected items?' ) );
 
        parent::display($tpl);
    }
}
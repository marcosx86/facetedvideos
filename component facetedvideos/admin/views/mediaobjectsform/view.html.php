<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view' );
 
class FacetedVideosViewMediaObjectsForm extends JView
{
    function display($tpl = null)
    {
		$isNew = ($this->record->id < 1);
		
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
        JToolBarHelper::title( JText::_( 'Video'.': <small><small>[ ' . $text.' ]</small></small>' ), 'generic.png' );
		
        JToolBarHelper::save();
		JToolBarHelper::addNew( 'saveandnew', JText::_( 'Save & New' ) );
        JToolBarHelper::apply();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}		
 
        parent::display($tpl);
    }
}
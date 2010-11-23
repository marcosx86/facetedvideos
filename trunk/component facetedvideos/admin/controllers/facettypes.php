<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

require_once( JPATH_COMPONENT.DS.'views'.DS.'facettypes'.DS.'view.html.php' );
require_once( JPATH_COMPONENT.DS.'views'.DS.'facettypesform'.DS.'view.html.php' );

class FacetedVideosControllerFacetTypes extends JController
{
	function __construct()
	{
		parent::__construct();
	
		$this->registerTask( 'add', 'edit' );
		$this->registerTask( 'apply', 'save' );
		$this->registerTask( 'saveandnew', 'save' );
		$this->registerTask( 'unpublish', 'publish' );
	}
	
	function edit()
	{
		JRequest::setVar( 'hidemainmenu', 1 );
				
		$db = &JFactory::getDBO();
		
		$cids = JRequest::getVar('cid',  0, '', 'array');
		
		$record = new stdClass();
		$record->id = (int) $cids[0];
		if ($record->id)
		{
			$query = ' SELECT id, title, showAsColumn, published '
				. ' FROM #__facetedvideos_facettypes '
				. ' WHERE id = ' . $record->id
			;
			
			$db->setQuery( $query );
			
			$record = $db->loadObject();
		}
		else
		{
			$record->title = null;
			$record->showAsColumn = 1;
			$record->published = 0;
		}
		
		$view = new FacetedVideosViewFacetTypesForm();
		$view->assignRef( 'record', $record );
		$view->display();
	}
	
	function publish()
	{
		$db = &JFactory::getDBO();
		
		$task = JRequest::getCmd( 'task' );
		$cids = JRequest::getVar( 'cid', null, 'post', 'array' );
		if (!$cids)
		{
			$cids = JRequest::getVar( 'cid', null, 'get', 'array' );
		}		
		
		$errors = 0;
		if ( count($cids) )
		{
			foreach($cids as $cid)
			{
				$query = ' UPDATE #__facetedvideos_facettypes '
					. ' SET published = ' . ($task == 'publish' ? '1' : '0')
					. ' WHERE id = ' . $cid
				;
				
				$db->setQuery($query);
				
				if (!$db->query())
				{
					$errors++;
				}
			}
		}
		
		$msg = '';
		if ($errors)
		{
			$msg = JText::_( 'Warning: one or more records could not be processed' );
		}
		else
		{
			$msg = JText::_( 'Record(s) processed' );
		}
	 
		$link = 'index.php?option=com_facetedvideos&controller=facettypes';
		$this->setRedirect($link, $msg);	
	}
	
	function remove()
	{
		$db = &JFactory::getDBO();
		
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		
		$errors = 0;
		if ( count($cids) )
		{
			foreach($cids as $cid)
			{
				$denied = false;
				
				$query = ' SELECT id FROM #__facetedvideos_facetobjects '
					. ' WHERE facetTypeId = ' . $cid
				;
				
				$db->setQuery($query);
				
				if ($db->query())
				{
					$denied = $db->getNumRows() > 0;
				}
				
				if ($denied)
				{
					$errors++;
				}
				else
				{				
					$query = ' DELETE FROM #__facetedvideos_facettypes '
						. ' WHERE id = ' . $cid
					;
					
					$db->setQuery($query);
					
					if (!$db->query())
					{
						$errors++;
					}
				}
			}
		}
		
		$msg = '';
		if($errors) {
			$msg = JText::_( 'Warning: one or more records could not be deleted' );
		} else {
			$msg = JText::_( 'Record(s) deleted' );
		}
	 
		$link = 'index.php?option=com_facetedvideos&controller=facettypes';
		$this->setRedirect($link, $msg);	
	}
	
	function save()
	{
		$data = JRequest::get( 'post' );
		
		$data["id"] = (int) $data["id"];
		$data["showAsColumn"] = (int) $data["showAsColumn"];
		$data["published"] = (int) $data["published"];
		
		$msg = '';

		if (trim($data["title"]) == "")
		{
			$msg = JText::_( 'You must enter the title of the facet type!' );
		}
		else
		{
			$db = &JFactory::getDBO();
			
			$errors = 0;
			
			$query = '';
			
			if ($data["id"])
			{
				$query = ' UPDATE #__facetedvideos_facettypes '
					. ' SET title = "' . $data["title"] . '", showAsColumn = ' . $data["showAsColumn"] . ', published = ' . $data["published"]
					. ' WHERE id = ' . $data["id"]
				;
			}
			else
			{
				$query = ' INSERT INTO #__facetedvideos_facettypes VALUES '
					. '(0, "' . $data["title"] . '", ' . $data["showAsColumn"] . ', ' . $data["published"] . ')'
				;
			}
			
			$db->setQuery($query);
				
			if (!$db->query())
			{
				$errors++;
			}
			
			if (!$data["id"])
			{
				$data["id"] = $db->insertid();
			}
			
			if ($errors)
			{
				$msg = JText::_( 'Error saving record!' );
			}
			else
			{
				$msg = JText::_( 'Record saved!' );
			}
		}

		switch (JRequest::getCmd( 'task' ))
		{
			case 'apply':
				$link = 'index.php?option=com_facetedvideos&controller=facettypes&task=edit&cid[]=' . $data["id"];
				break;
			case 'saveandnew':
				$link = 'index.php?option=com_facetedvideos&controller=facettypes&task=add';
				break;
			default:
				$link = 'index.php?option=com_facetedvideos&controller=facettypes';
				break;
		}
		
		$this->setRedirect($link, $msg);
	}

	function cancel()
	{
		$link = 'index.php?option=com_facetedvideos&controller=facettypes';
		$msg = JText::_( 'Operation cancelled.' );
		$this->setRedirect($link, $msg);		
	}
	
    function display($tpl = null)
    {
		$db = &JFactory::getDBO();
		
		$query = ' SELECT id, title, showAsColumn, published '
			. ' FROM #__facetedvideos_facettypes '
			. ' ORDER BY title ASC '
		;
		
		$db->setQuery($query);
		
		$records = $db->loadObjectList();
		
        $view = new FacetedVideosViewFacetTypes();
		$view->assignRef( 'records', $records );
		$view->display($tpl);
    }

}
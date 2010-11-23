<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');

require_once( JPATH_COMPONENT.DS.'views'.DS.'facetobjects'.DS.'view.html.php' );
require_once( JPATH_COMPONENT.DS.'views'.DS.'facetobjectsform'.DS.'view.html.php' );
 
class FacetedVideosControllerFacetObjects extends JController
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
			$query = ' SELECT id, facetTypeId, title, published '
				. ' FROM #__facetedvideos_facetobjects '
				. ' WHERE id = ' . $record->id
			;
			
			$db->setQuery( $query );
			
			$record = $db->loadObject();
		}
		else
		{
			$record->facetTypeId = 0;
			$record->title = "";
			$record->published = 0;
		}
		
		$query = ' SELECT id, title, showAsColumn, published '
			. ' FROM #__facetedvideos_facettypes '
			. ' ORDER BY title ASC '
		;
		
		$db->setQuery($query);
		
		$list1 = $db->loadObjectList();

		$view = new FacetedVideosViewFacetObjectsForm();
		$view->assignRef( 'record', $record );
		$view->assignRef( 'list_facettypes', $list1 );
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
				$query = ' UPDATE #__facetedvideos_facetobjects '
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
	 
		$link = 'index.php?option=com_facetedvideos&controller=facetobjects';
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
				$query = ' DELETE FROM #__facetedvideos_mediafacetassoc '
					. ' WHERE facetObjectId = ' . $cid
				;
				
				$db->setQuery($query);
				
				if (!$db->query())
				{
					$errors++;
				}
				
				$query = ' DELETE FROM #__facetedvideos_facetobjects '
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
		if($errors) {
			$msg = JText::_( 'Warning: one or more records could not be deleted' );
		} else {
			$msg = JText::_( 'Record(s) deleted' );
		}
	 
		$link = 'index.php?option=com_facetedvideos&controller=facetobjects';
		$this->setRedirect($link, $msg);	
	}
	
	function save()
	{
		$data = JRequest::get( 'post' );
		
		$data["id"] = (int) $data["id"];
		$data["facetTypeId"] = (int) $data["facetTypeId"];
		$data["published"] = (int) $data["published"];
		
		$msg = '';
		$error = false;

		if ($data["facetTypeId"] == 0)
		{
			$msg = JText::_( 'You must select the facet type where the facet will reside!' );
			$error = true;
		}		
		
		if (trim($data["title"]) == "")
		{
			$msg = JText::_( 'You must enter the title of the facet!' );
			$error = true;
		}
		
		if (!$error)
		{
			$db = &JFactory::getDBO();
			
			$errors = 0;
			
			$query = '';
			
			if ($data["id"])
			{
				$query = ' UPDATE #__facetedvideos_facetobjects '
					. ' SET title = "' . $data["title"] . '", facetTypeId = ' . $data["facetTypeId"] . ', published = ' . $data["published"]
					. ' WHERE id = ' . $data["id"]
				;
			}
			else
			{
				$query = ' INSERT INTO #__facetedvideos_facetobjects VALUES '
					. '(0, ' . $data["facetTypeId"] . ', "' . $data["title"] . '", ' . $data["published"] . ')'
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
				$link = 'index.php?option=com_facetedvideos&controller=facetobjects&task=edit&cid[]=' . $data["id"];
				break;
			case 'saveandnew':
				$link = 'index.php?option=com_facetedvideos&controller=facetobjects&task=add';
				break;
			default:
				$link = 'index.php?option=com_facetedvideos&controller=facetobjects';
				break;
		}		
		
		$this->setRedirect($link, $msg);
	}	
	
	function cancel()
	{
		$link = 'index.php?option=com_facetedvideos&controller=facetobjects';
		$msg = JText::_( 'Operation cancelled.' );
		$this->setRedirect($link, $msg);		
	}
	
    function display($tpl = null)
    {
		$db = &JFactory::getDBO();
			
		$query = ' SELECT b.id, a.title as typetitle, b.title, b.published '
			. ' FROM #__facetedvideos_facetobjects b '
			. ' INNER JOIN #__facetedvideos_facettypes a ON a.id = b.facetTypeId '
			. ' ORDER BY a.title ASC, b.title ASC '
		;
		
		$db->setQuery($query);
		
		$records = $db->loadObjectList();
	
        $view = new FacetedVideosViewFacetObjects();
		$view->assignRef( 'records', $records );
		$view->display($tpl);
    }
 
}
<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');

require_once( JPATH_COMPONENT.DS.'views'.DS.'mediaobjects'.DS.'view.html.php' );
require_once( JPATH_COMPONENT.DS.'views'.DS.'mediaobjectsform'.DS.'view.html.php' );
 
class FacetedVideosControllerMediaObjects extends JController
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
		
		$params = &JComponentHelper::getParams( 'com_facetedvideos' );	
		
		$cids = JRequest::getVar('cid',  0, '', 'array');
		
		// carregar o record
		$record = new stdClass();
		$record->id = (int) $cids[0];
		if ($record->id)
		{
			$query = ' SELECT id, filename, title, lenght, published '
				. ' FROM #__facetedvideos_mediaobjects '
				. ' WHERE id = ' . $record->id
			;
			
			$db->setQuery( $query );
			
			$record = $db->loadObject();
		}
		else
		{
			$record->filename = "";
			$record->title = "";
			$record->lenght = 0;
			$record->published = 0;
		}		

		// carregar as facetas (todas)
		$query = ' SELECT b.id, a.title as typetitle, b.title '
			. ' FROM #__facetedvideos_facetobjects b '
			. ' INNER JOIN #__facetedvideos_facettypes a ON a.id = b.facetTypeId '
			. ' ORDER BY a.title ASC, b.title ASC '
		;
		
		$db->setQuery($query);
		
		$list1 = $db->loadObjectList();
		
		foreach ($list1 as $k => $v)
		{
			$list1[$k]->displaystring = $list1[$k]->typetitle . " > " . $list1[$k]->title;
		}

		// carregar as IDs das facetas selecionadas
		$list2 = array();
		
		if ($record->id)
		{
			$query = ' SELECT a.facetObjectId FROM jos_facetedvideos_mediafacetassoc a '
				. ' INNER JOIN jos_facetedvideos_facetobjects b ON b.id = a.facetObjectId  '
				. ' WHERE a.mediaId = ' . $record->id;
			;
		
			$db->setQuery($query);
			
			$list2 = $db->loadObjectList();	
			
			foreach ($list2 as $k => $v)
			{
				$list2[$k] = $list2[$k]->facetObjectId;
			}
		}
	
		$dirname = $params->get( 'videodir' );
		$path = JPATH_ROOT.DS.'media'.DS.$dirname;
		$list3 = null;
		if (JFolder::exists($path))
		{
			$list3 = JFolder::files($path);
			
			$_list3 = array();
			
			foreach ($list3 as $elem)
			{
				$obj = new stdClass();
				$obj->id = $elem;
				$obj->value = utf8_encode($elem);
				$_list3[] = $obj;
			}
			
			$list3 = $_list3;
		}
		
		$view = new FacetedVideosViewMediaObjectsForm();
		$view->assignRef( 'record', $record );
		$view->assignRef( 'list_facetobjects', $list1 );
		$view->assignRef( 'list_selectedfacets', $list2 );
		$view->assignRef( 'list_filenames', $list3 );
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
				$query = ' UPDATE #__facetedvideos_mediaobjects '
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
	 
		$link = 'index.php?option=com_facetedvideos&controller=mediaobjects';
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
					. ' WHERE mediaId = ' . $cid
				;
				
				$db->setQuery($query);
				
				if (!$db->query())
				{
					$errors++;
				}

				$query = ' DELETE FROM #__facetedvideos_mediaobjects '
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
	 
		$link = 'index.php?option=com_facetedvideos&controller=mediaobjects';
		$this->setRedirect($link, $msg);	
	}
	
	function save()
	{
		$data = JRequest::get( 'post' );
		
		$data["id"] = (int) $data["id"];
		$data["facetObjectIds"] = (array) $data["facetObjectIds"];
		$data["lenght"] = (int) $data["lenght"];
		$data["published"] = (int) $data["published"];
		
		$msg = '';
		$error = false;
		
		if (trim($data["title"]) == "")
		{
			$msg = JText::_( 'You must enter the title of the video!' );
			$error = true;
		}

		if ($data["lenght"] <= 0)
		{
			$msg = JText::_( 'You must enter the lenght of the video (must be an positive integer number of seconds)!' );
			$error = true;
		}
		
		if (!$error)
		{
			$db = &JFactory::getDBO();
			
			$errors = 0;
			
			$query = '';
			
			if ($data["id"])
			{
				$query = ' DELETE FROM #__facetedvideos_mediafacetassoc '
					. ' WHERE mediaId = ' . $data["id"]
				;
				
				$db->setQuery($query);
			
				if (!$db->query())
				{
					$errors++;
				}
				
				if (count($data["facetObjectIds"]))
				{
					$query = '';
					
					foreach ($data["facetObjectIds"] as $facetId)
					{
						if ($query != "")
						{
							$query .=  sprintf(", (0, %d, %d)", $data["id"], $facetId);
						}
						else
						{
							$query = sprintf("(0, %d, %d)", $data["id"], $facetId);
						}
					}
					
					$query = ' INSERT INTO #__facetedvideos_mediafacetassoc VALUES '
						. $query
					;
					
					$db->setQuery($query);
				
					if (!$db->query())
					{
						$errors++;
					}
				}
				
				$query = ' UPDATE #__facetedvideos_mediaobjects '
					. ' SET filename = "' . $data["filename"] . '", '
					. ' title = "' . $data["title"] . '", '
					. ' lenght = ' . $data["lenght"] . ', '
					. ' published = ' . $data["published"]
					. ' WHERE id = ' . $data["id"]
				;
			
				$db->setQuery($query);
				
				if (!$db->query())
				{
					$errors++;
				}
				
				if (!$data["id"])
				{
					$data["id"] = $db->insertid();
				}
			}
			else
			{
				$datetimenow = date("Y-m-d H:i:s");
				
				$query = ' INSERT INTO #__facetedvideos_mediaobjects VALUES '
					. ' (0, "' . $data["filename"] . '", "' . $data["title"] . '", '
					. $data["lenght"] . ', "' . $datetimenow . '", '
					. $data["published"] . ', 0)'
				;
			
				$db->setQuery($query);
				
				if (!$db->query())
				{
					$errors++;
				}
				
				if (!$data["id"])
				{
					$data["id"] = $db->insertid();
				}

				if (count($data["facetObjectIds"]))
				{
					$query = '';
					
					foreach ($data["facetObjectIds"] as $facetId)
					{
						if ($query != "")
						{
							$query .=  sprintf(", (0, %d, %d)", $data["id"], $facetId);
						}
						else
						{
							$query = sprintf("(0, %d, %d)", $data["id"], $facetId);
						}
					}
					
					$query = ' INSERT INTO #__facetedvideos_mediafacetassoc VALUES '
						. $query
					;
					
					$db->setQuery($query);
				
					if (!$db->query())
					{
						$errors++;
					}
				}
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
				$link = 'index.php?option=com_facetedvideos&controller=mediaobjects&task=edit&cid[]=' . $data["id"];
				break;
			case 'saveandnew':
				$link = 'index.php?option=com_facetedvideos&controller=mediaobjects&task=add';
				break;
			default:
				$link = 'index.php?option=com_facetedvideos&controller=mediaobjects';
				break;
		}		
		
		$this->setRedirect($link, $msg);
}
	
	function cancel()
	{
		$link = 'index.php?option=com_facetedvideos&controller=mediaobjects';
		$msg = JText::_( 'Operation cancelled.' );
		$this->setRedirect($link, $msg);		
	}
	
	function display($tpl = null)
    {
		$db = &JFactory::getDBO();
			
		$query = ' SELECT id, filename, title, lenght, added, published, hits '
			. ' FROM #__facetedvideos_mediaobjects '
			. ' ORDER BY added DESC '
		;
		
		$db->setQuery($query);
		
		$records = $db->loadObjectList();
		
        $view = new FacetedVideosViewMediaObjects();
		$view->assignRef( 'records', $records );
		$view->display($tpl);
    }
 
}
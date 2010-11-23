<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');

require_once( JPATH_COMPONENT.DS.'views'.DS.'watch'.DS.'view.html.php' );
 
class FacetedVideosControllerWatch extends JController
{
    function display()
    {
		$db = &JFactory::getDBO();
		
		$cid = JRequest::getVar( 'cid', null, 'get', 'array' );
		if ($cid == null)
		{
			$params =& JComponentHelper::getParams( 'com_facetedvideos' );
			
			$cid = $params->get( 'videoid', 0 );
		}
		else
		{
			$cid = (int) $cid[0];
		}
		
		$query = ' SELECT published '
			. ' FROM #__facetedvideos_mediaobjects '
			. ' WHERE id = ' . $cid
		;
		
		$db->setQuery( $query );
		
		$objs = $db->loadObjectList();
		
		$denied = false;
		$record = null;
		$facets = null;
		
		if (count($objs))
		{
			if (!($denied = !$objs[0]->published))
			{
				$query = ' SELECT filename, title, added, hits '
					. ' FROM #__facetedvideos_mediaobjects '
					. ' WHERE id = ' . $cid
				;
				
				$db->setQuery( $query );
				
				$records = $db->loadObjectList();
				
				$record = $records[0];
				
				$query = ' SELECT t.title as facettype, o.title as facettitle '
					. ' FROM #__facetedvideos_mediafacetassoc a '
					. ' INNER JOIN #__facetedvideos_facetobjects o ON o.id = a.facetObjectId '
					. ' INNER JOIN #__facetedvideos_facettypes t ON t.id = o.facetTypeId '
					. ' WHERE a.mediaId = ' . $cid
					. ' ORDER BY t.title ASC, o.title ASC '
				;
				
				$db->setQuery( $query );
				
				$facets = $db->loadObjectList();
				
				$query = ' UPDATE #__facetedvideos_mediaobjects '
					. ' SET hits = ' . ($record->hits + 1)
					. ' WHERE id = ' . $cid
				;
				
				$db->setQuery( $query );
				
				$db->query();
			}
		}
		
        $view = new FacetedVideosViewWatch();
		$view->assignRef( 'record', $record );
		$view->assignRef( 'denied', $denied );
		$view->assignRef( 'facets', $facets );
		$view->display();	
	}
}
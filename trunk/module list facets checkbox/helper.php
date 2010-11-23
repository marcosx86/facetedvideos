<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class modFacetedVideosFacetSelectionHelper
{
	function prepare()
	{
		$doc =& JFactory::getDocument();
		$doc->addStyleSheet( '/modules/mod_facetedvideos_facetselection/tmpl/default.css' );
	}

	function getFacets( $params )
	{
		// get a reference to the database
		$db = &JFactory::getDBO();	
		
		//
		$query = ' SELECT f.id, f.title as facettitle, t.title as facettype '
			. ' FROM jos_facetedvideos_facetobjects f '
			. ' INNER JOIN jos_facetedvideos_facettypes t ON t.id = f.facetTypeId '
			. ' WHERE t.published = 1 AND f.published = 1 '
			. ' ORDER BY t.title ASC, f.title ASC '
		;
		
		$db->setQuery( $query );
		
		$list = $db->loadObjectList();
		
		//
		$cookie = JRequest::getVar( 'FV_SELFACETS', '', 'COOKIES', 'string' );
		$selected = explode(',', $cookie);
		
		$facettype = '';
		$facettitle = '';
		$output = array();
		$innerlist = null;
		
		foreach ($list as $item)
		{
			if ($item->facettype != $facettype)
			{
				unset($innerlist);
				$innerlist = array();
			
				$obj = new stdClass();				
				$obj->list = &$innerlist;
				$obj->title = $facettype = $item->facettype;
				
				$output[] = $obj;
			}
			
			$obj = new stdClass();
			$obj->id = $item->id;
			$obj->title = $item->facettitle;
			$obj->checked = in_array($obj->id, $selected);
			$innerlist[] = $obj;			
		}
		
		return $output;
	}
}
?>
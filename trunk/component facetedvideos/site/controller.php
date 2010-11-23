<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport('joomla.application.component.controller');

require_once( JPATH_COMPONENT.DS.'views'.DS.'navigation'.DS.'view.html.php' );
 
class FacetedVideosController extends JController
{
    function display()
    {
		$db = &JFactory::getDBO();
		
		//
		$cookie = trim( JRequest::getVar( 'FV_SELFACETS', '', 'COOKIES', 'string' ) );
		$selected = ($cookie == '' ? array() : explode(',', $cookie));
		$having = count($selected);

		$where = ' AND ( a.facetObjectId = ' . implode( ' OR a.facetObjectId = ', $selected ) . ' ) ';
		
		// recebe todos os vídeos que estão selecionados via cookies
		$query = ' SELECT v.id, v.title, v.lenght, v.added, v.hits '
			. ' FROM #__facetedvideos_mediaobjects v '
			. ' INNER JOIN #__facetedvideos_mediafacetassoc a ON a.mediaId = v.id '
			. ' WHERE v.published = 1 '
			. (count($selected) > 0 ? $where : '')
			. ' GROUP BY v.id '
			. ($having > 0 ? ' HAVING SUM(1) = ' . $having : '')
			. ' ORDER BY v.added DESC '
		;
		
		$db->setQuery( $query );
		
		$records = $db->loadObjectList();
		
		$videoIds = array();
		
		if (count($records))
		{
			foreach ($records as $item)
			{
				$videoIds[] = $item->id;
			}
		}
		
		// recebe todas os grupos de facetas que serão exibidos como colunas
		$query = ' SELECT id, title '
			. ' FROM #__facetedvideos_facettypes '
			. ' WHERE showAsColumn = 1 '
			. ' ORDER BY title ASC '
		;
		
		$db->setQuery( $query );
		
		$extraColumns = $db->loadObjectList();		
		
		$where = ' AND (a.mediaId = ' . implode( ' OR a.mediaId = ', $videoIds ) . ' ) ';
		
		// recebe todas as facetas que estão associadas aos vídeos previamente recebidos na primeira query
		$query = ' SELECT a.mediaId, o.title, o.facetTypeId, ft.published as ftpub, o.published as opub '
			. ' FROM jos_facetedvideos_mediafacetassoc a '
			. ' INNER JOIN jos_facetedvideos_facetobjects o ON a.facetObjectId = o.id '
			. ' INNER JOIN #__facetedvideos_facettypes ft ON o.facetTypeId = ft.id '
			. ' WHERE 1=1 '
			. $where
			. ' ORDER BY a.mediaId ASC, o.title ASC '
		;

		$db->setQuery( $query );
		
		$associatedFacets = $db->loadObjectList();
		
		// distribui as facetas dentro de cada vídeo, em colunas
		// problema: os videos em facetas/tipos ocultos estao vindo da SQL, e sendo tratadas só aqui!
		if (count($records) && count($extraColumns) && count($associatedFacets))
		{
			foreach ($records as $video)
			{
				$video->extraColumns = array();
				$video->isHidden = false;
				
				foreach ($extraColumns as $ftype)
				{
					$str = '';
					
					foreach ($associatedFacets as $data)
					{
						if ($data->mediaId == $video->id && $data->facetTypeId == $ftype->id)
						{
							$str .= ($str == '' ? '' : ', ') . $data->title;
							if ($data->ftpub == 0 || $data->opub == 0)
							{
								$video->isHidden = true;
							}
						}
					}
					
					$video->extraColumns[] = $str;
				}
			}				
		}
			
        $view = new FacetedVideosViewNavigation();
		$view->assignRef( 'records', $records );
		$view->assignRef( 'selectedfacets', $selected );
		$view->assignRef( 'extracolumns', $extraColumns );
		$view->display();	
	}
}
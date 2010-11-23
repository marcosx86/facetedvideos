<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
// Include the syndicate functions only once
require_once( dirname(__FILE__).DS.'helper.php' );

modFacetedVideosFacetSelectionHelper::prepare(); 
$data = modFacetedVideosFacetSelectionHelper::getFacets( $params );

require( JModuleHelper::getLayoutPath( 'mod_facetedvideos_facetselection' ) );
?>
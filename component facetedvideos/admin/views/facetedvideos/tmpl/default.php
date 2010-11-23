<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="cpanel">
<?php
	function quickiconButton( $link, $image, $text )
	{
		global $mainframe;
		$lang		=& JFactory::getLanguage();
		$template	= $mainframe->getTemplate();
		?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
					<?php echo JHTML::_('image.site',  $image, '/templates/'. $template .'/images/header/', NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span></a>
			</div>
		</div>
		<?php
	}
	
	$link = 'index.php?option=com_facetedvideos&amp;controller=facettypes';
	quickiconButton( $link, 'icon-48-generic.png', JText::_( 'Facet Types' ) );

	$link = 'index.php?option=com_facetedvideos&amp;controller=facetobjects';
	quickiconButton( $link, 'icon-48-generic.png', JText::_( 'Facets' ) );

	$link = 'index.php?option=com_facetedvideos&amp;controller=mediaobjects';
	quickiconButton( $link, 'icon-48-generic.png', JText::_( 'Videos' ) );
?>
</div>
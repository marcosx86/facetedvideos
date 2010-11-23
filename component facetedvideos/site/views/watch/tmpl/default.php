<?php
// No direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="componentheading"><?php echo JText::_( 'Faceted Videos - Watch Video' ); ?>
</div>
<div class="videotitle">
	<?php echo $this->record->title; ?>
</div>
<div class="videoplayer" id="componentvideoplayer">
<?php
	$filename = JURI::base() . 'media/' . $this->videodir . '/' . $this->record->filename;
	$width = $this->videowidth;
	$height = $this->videoheight;
?>
</div>
<script type="text/javascript" src="<?php echo JURI::base() . 'components/com_facetedvideos/assets/swfobject.js'; ?>"></script>
<script>
  var so = new SWFObject('<?php echo JURI::base() . 'components/com_facetedvideos/assets/player.swf'; ?>','mpl','<?php echo $width; ?>','<?php echo $height; ?>','9');
  so.addParam('allowfullscreen','true');
  so.addParam('allowscriptaccess','always');
  so.addParam('wmode','opaque');
  so.addVariable('file','<?php echo $filename; ?>');
  so.write('componentvideoplayer');
</script>
<div>
	<h4><?php echo JText::_( 'Video Info' ); ?></h4>
	<div>
		<h5><?php echo JText::_( 'Added on:') . ' ' . $this->record->added; ?></h5>
		<h5><?php echo JText::_( 'Hits:' ) . ' ' . $this->record->hits; ?></h5>
		<h5><?php echo JText::_( 'Associated Facets:' ); ?></h5>
		<?php
			foreach($this->facets as $facet)
			{
		?>
		<div class="facet"><?php echo $facet->facettype . ' &gt; ' . $facet->facettitle; ?></div>
		<?php
			}
		?>
	</div>
</div>
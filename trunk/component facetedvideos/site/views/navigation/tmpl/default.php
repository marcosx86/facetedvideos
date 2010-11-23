<?php
// No direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="componentheading"><?php echo JText::_( 'Faceted Videos - Navigation' ); ?>
</div>
<?php
	if (count($this->records)):
?>
<div class="videolist">
<table cellpadding="0" cellspacing="0">
<thead>
	<th><?php echo JText::_( 'Title' ); ?></th>
	<th><?php echo JText::_( 'Lenght' ); ?></th>
	<th><?php echo JText::_( 'Added' ); ?></th>
	<th><?php echo JText::_( 'Hits' ); ?></th>
<?php
	if ($this->showextracolumns)
	{
		foreach ($this->extracolumns as $cols)
		{
?>
	<th><?php echo $cols->title; ?></th>
<?php
		}
	}
?>
</thead>
<?php
	foreach ($this->records as $video)
	{
		$link = JRoute::_( 'index.php?option=com_facetedvideos&controller=watch&view=watch&cid=' . $video->id );
	
		if ($video->isHidden)
		{
			continue;
		}
	
		$min = $video->lenght % 60;
		$lenght = ((int)($video->lenght / 60)) . ":" . ($min < 10 ? '0' . $min : $min);
?>
	<tr>
		<td><a href="<?php echo $link; ?>"><?php echo $video->title; ?></a></td>
		<td><?php echo $lenght; ?></td>
		<td><?php echo $video->added; ?></td>
		<td><?php echo $video->hits; ?></td>
<?php
		if ($this->showextracolumns)
		{
			foreach ($video->extraColumns as $str)
			{
?>
		<td><?php echo $str; ?></td>
<?php
			}
		}
?>		
	</tr>
<?php
	}
?>
</table>
</div>
<?php
	else:
?>
<div class="message"><?php echo JText::_( 'No videos matching selected facets, or there are no videos available.' ); ?></div>
<?php	
	endif;
?>
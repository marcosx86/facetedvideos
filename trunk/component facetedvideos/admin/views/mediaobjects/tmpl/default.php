<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="20">
				<?php echo JText::_( 'ID' ); ?>
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->records ); ?>);" />
			</th>
			<th width="300">
				<?php echo JText::_( 'Filename' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Title' ); ?>
			</th>
			<th width="60">
				<?php echo JText::_( 'Lenght' ); ?>
			</th>
			<th width="140">
				<?php echo JText::_( 'Added on' ); ?>
			</th>
			<th width="60">
				<?php echo JText::_( 'Published' ); ?>
			</th>
			<th width="60">
				<?php echo JText::_( 'Hits' ); ?>
			</th>
		</tr>            
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->records ); $i < $n; $i++)
	{
		$row =& $this->records[$i];
		$checked = JHTML::_( 'grid.id', $i, $row->id );
		$link = JRoute::_( 'index.php?option=com_facetedvideos&controller=mediaobjects&task=edit&cid[]='. $row->id );
		$min = $row->lenght % 60;
		$row->lenght = ((int)($row->lenght / 60)) . ":" . ($min < 10 ? '0' . $min : $min);
		$pubact = $row->published ? "unpublish" : "publish";		
		$publink = JRoute::_( 'index.php?option=com_facetedvideos&controller=mediaobjects&task=' . $pubact . '&cid[]=' . $row->id );
		
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<?php echo $row->filename; ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
			</td>
			<td>
				<?php echo $row->lenght; ?>
			</td>
			<td>
				<?php echo $row->added; ?>
			</td>
			<td>
				<a href="<?php echo $publink; ?>"><?php echo ($row->published ? "Yes" : "No"); ?></a>
			</td>
			<td>
				<?php echo $row->hits; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
	</table>
</div>
 
<input type="hidden" name="option" value="com_facetedvideos" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="mediaobjects" />
 
</form>
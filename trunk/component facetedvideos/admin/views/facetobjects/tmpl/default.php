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
			<th>
				<?php echo JText::_( 'Title' ); ?>
			</th>
			<th>
				<?php echo JText::_( 'Facet Type' ); ?>
			</th>
			<th width="100">
				<?php echo JText::_( 'Published' ); ?>
			</th>
		</tr>            
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->records ); $i < $n; $i++)
	{
		$row =& $this->records[$i];
		$checked = JHTML::_( 'grid.id', $i, $row->id );
		$link = JRoute::_( 'index.php?option=com_facetedvideos&controller=facetobjects&task=edit&cid[]='. $row->id );
		$pubact = $row->published ? "unpublish" : "publish";		
		$publink = JRoute::_( 'index.php?option=com_facetedvideos&controller=facetobjects&task=' . $pubact . '&cid[]=' . $row->id );

		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
				<a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
			</td>
			<td>
				<?php echo $row->typetitle; ?>
			</td>
			<td>
				<a href="<?php echo $publink; ?>"><?php echo ($row->published ? "Yes" : "No"); ?></a>
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
<input type="hidden" name="controller" value="facetobjects" />
 
</form>
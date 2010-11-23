<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div class="fv_sfm_checkitems">
<?php
	foreach ($data as $group)
	{
?>
<div class="group"><h4><?php echo $group->title; ?></h4>
<div class="items">
<?php
	foreach ($group->list as $item)
	{
?>
<span class="item"><input class="chk" type="checkbox" <?php if ($item->checked) echo 'checked="checked" '; ?>value="<?php echo $item->id; ?>" /><?php echo $item->title; ?></span>
<?php
	}
?>
</div>
</div>
<?php
	}
?>
<div class="lowerpanel">
<input class="rst" type="button" value="<?php echo JText::_( 'Clear selection' ); ?>" />
</div>
</div>
<script type="text/javascript">
function postSelection()
{
	var str = '';
	jQuery("div.fv_sfm_checkitems input.chk:checked").each(function(index){
		var val = jQuery(this).val();
		str += (str == '' ? val : ',' + val);
	});
	
	document.cookie = 'FV_SELFACETS=' + str;
	window.location.reload();
}

function resetSelection()
{
	document.cookie = 'FV_SELFACETS=';
	window.location.reload();
}

jQuery("div.fv_sfm_checkitems input.chk").each(function(index){
	jQuery(this).click(postSelection);
})

jQuery("div.fv_sfm_checkitems input.rst").click(resetSelection);
</script>
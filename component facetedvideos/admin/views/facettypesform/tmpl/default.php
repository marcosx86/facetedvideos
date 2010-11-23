<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
    <fieldset class="adminform">
        <legend><?php echo JText::_( 'Details' ); ?></legend>
        <table class="admintable">
        <tr>
            <td width="100" align="right" class="key">
                <label for="title">
                    <?php echo JText::_( 'Title' ); ?>:
                </label>
            </td>
            <td>
                <input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?php echo $this->record->title;?>" />
            </td>
        </tr>
		<tr>
			<td class="key">
				<?php echo JText::_( 'Show as column?' ); ?>:
			</td>
			<td>
				<?php echo JHTML::_('select.booleanlist',  'showAsColumn', '', ($this->record != null ? $this->record->showAsColumn : 1) ); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php echo JText::_( 'Published' ); ?>:
			</td>
			<td>
				<?php echo JHTML::_('select.booleanlist',  'published', '', $this->record->published ); ?>
			</td>
		</tr>
    </table>
    </fieldset>
</div>
 
<div class="clr"></div>
 
<input type="hidden" name="option" value="com_facetedvideos" />
<input type="hidden" name="id" value="<?php echo $this->record->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="controller" value="facettypes" />
</form>
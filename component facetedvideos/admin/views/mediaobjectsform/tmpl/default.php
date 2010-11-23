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
            <td width="100" align="right" class="key">
                <label for="filename">
                    <?php echo JText::_( 'Filename' ); ?>:
                </label>
            </td>
            <td>
                <?php
					echo JHTML::_('select.genericlist', $this->list_filenames, 'filename', 'class="inputbox" size="1"','id', 'value', $this->record->filename );	
				?>
            </td>
        </tr>
        <tr>
            <td width="100" align="right" class="key">
                <label for="facets">
                    <?php echo JText::_( 'Facets' ); ?>:
                </label>
            </td>
            <td>
                <?php
					echo JHTML::_('select.genericlist', $this->list_facetobjects, 'facetObjectIds[]', 'class="inputbox" multiple="multiple" size="10"','id', 'displaystring', $this->list_selectedfacets );	
				?>
            </td>
        </tr>
        <tr>
            <td width="100" align="right" class="key">
                <label for="lenght">
                    <?php echo JText::_( 'Lenght (in secs.)' ); ?>:
                </label>
            </td>
            <td>
                <input class="text_area" type="text" name="lenght" id="lenght" size="10" maxlength="4" value="<?php echo $this->record->lenght;?>" />
            </td>
        </tr>
		<tr>
			<td class="key">
                <label for="published">
					<?php echo JText::_( 'Published' ); ?>:
                </label>
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
<input type="hidden" name="controller" value="mediaobjects" />
</form>
<?php if (!defined('IN_CMS')) { exit(); } ?>
<?php $questionId = (isset($questionId)) ? $questionId : 0; ?>
<?php $pageId = (isset($pageId)) ? $pageId : 1; ?>
<?php if ( (count($djg_poll['pollq_id'])==0) or ($djg_poll['pollq_id'] != $questionId ) ): ?>
<?php redirect(get_url('plugin/djg_poll/polls/'.$pageId)); ?>
<?php else: ?>
<h1><?php echo __('Edit: :question',array(':question'=>'('.$djg_poll['pollq_id'].') ' . Djgpoll::trim_text($djg_poll['pollq_question']))); ?></h1>
<div id="djg_poll">
<form id="djg_poll_form" action="<?php echo get_url('plugin/djg_poll/edit/'.$questionId.'/'.$pageId); ?>" method="post">
    <fieldset style="padding: 0.5em;">
        <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
		<input type="hidden" name="djg_poll[questionId]" value="<?php echo $questionId; ?>" />
		<tr>
			<td class="label"><?php echo __('Multiple'); ?>: </label></td>
			<td class="field">
				<select id="subject" name="djg_poll[multiple]">
					<option value="0" <?php if($djg_poll['pollq_multiple'] == "0") echo 'selected="selected"' ?>><?php echo __('no'); ?></option>
					<option value="1" <?php if($djg_poll['pollq_multiple'] == "1") echo 'selected="selected"' ?>><?php echo __('yes'); ?></option>
				</select>	
			</td>
			<td class="help"><?php echo __('Allows users to select more than one answer.'); ?></td>
		</tr>
		<tr>
			<td class="label"><?php echo __('Is active'); ?>: </label></td>
			<td class="field">
				<select id="subject" name="djg_poll[active]">
					<option value="0" <?php if($djg_poll['pollq_active'] == "0") echo 'selected="selected"' ?>><?php echo __('no'); ?></option>
					<option value="1" <?php if($djg_poll['pollq_active'] == "1") echo 'selected="selected"' ?>><?php echo __('yes'); ?></option>
				</select>	
			</td>
			<td class="help"><?php echo __('Possible to vote.'); ?></td>
		</tr>
		<tr>
			<td class="label"><?php echo __('Time between votes'); ?>: </label></td>
			<td class="field">
				<select id="timestamp" name="djg_poll[timestamp]">
					<option value="0" <?php if($djg_poll['pollq_timestamp'] == 0) echo 'selected="selected"' ?>><?php echo __('no restrictions'); ?></option>
					<option value="1" <?php if($djg_poll['pollq_timestamp'] == 1) echo 'selected="selected"' ?>><?php echo __('every hour'); ?></option>
					<option value="24" <?php if($djg_poll['pollq_timestamp'] == 1*24) echo 'selected="selected"' ?>><?php echo __('once a day'); ?></option>
					<option value="168" <?php if($djg_poll['pollq_timestamp'] == 1*24*7) echo 'selected="selected"' ?>><?php echo __('every 7 Days'); ?></option>
					<option value="720" <?php if($djg_poll['pollq_timestamp'] == 1*24*30) echo 'selected="selected"' ?>><?php echo __('every 30 Days'); ?></option>
					<option value="8760" <?php if($djg_poll['pollq_timestamp'] == 1*24*365) echo 'selected="selected"' ?>><?php echo __('every 365 Days'); ?></option>
				</select>
			</td>
			<td class="help"><?php echo __('You can set the time between successive votes.'); ?></td>
		</tr>
		<tr>
			<td class="label"><?php echo __('Lifetime (optional)'); ?></label></td>
			<td class="field"></td>
			<td class="help"><?php echo __('You can set lifetime of poll. Fomat is: <strong>YYYY-MM-DD HH:MM:SS</strong>'); ?></td>
		</tr>			
		<tr>
			<td class="label"><?php echo __('Start'); ?>: </label></td>
			<td class="field">
				<input id="startvote" name="djg_poll[startvote]" type="text" value="<?php if($djg_poll['pollq_startvote']) echo $djg_poll['pollq_startvote']; ?>" />
				<span class="now_date"><?php echo __('now'); ?></span> <span class="clear_date"><?php echo __('clear'); ?></span>
			</td>
			<td class="help"><?php echo __("If You don't want to use lifetime just leave empty fields."); ?></td>
		</tr>
		<tr>
			<td class="label"><?php echo __('End'); ?>: </label></td>
			<td class="field">
				<input id="endvote" name="djg_poll[endvote]" type="text" value="<?php if($djg_poll['pollq_endvote']) echo $djg_poll['pollq_endvote']; ?>" /> <span class="now_date"><?php echo __('now'); ?></span> <span class="clear_date"><?php echo __('clear'); ?></span>
			</td>
			<td class="help"><?php echo __('You can use keybord cursor to change value.'); ?></td>
		</tr>
      <tr>
        <td class="label"></td>
        <td class="field">
				</td>
				<td class="help"></td>
			</tr>
        </table>
    </fieldset>
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save changes'); ?>" /> | <a href="<?php echo get_url('plugin/djg_poll/polls/'.$pageId); ?>"><?php echo __('Back to list of all polls.'); ?></a>   
    </p>
</form>
</div>
<?php endif; ?> 
<script type="text/javascript">
// <![CDATA[
	$(document).ready(function() {
		$.datetimeEntry.setDefaults({spinnerImage: '<?php echo URL_PUBLIC; ?>wolf/plugins/djg_poll/js/spinnerDefault.png'});	
		if($("#startvote").val()=='0000-00-00 00:00:00'){ $("#startvote").val(''); };
		if($("#endvote").val()=='0000-00-00 00:00:00'){	$("#endvote").val(''); };
		$("#startvote").datetimeEntry({datetimeFormat: 'Y-O-D H:M:S'});
		$("#endvote").datetimeEntry({datetimeFormat: 'Y-O-D H:M:S'});		
		$('input').attr('autocomplete','off');
	});
	/*  date buttons */
	$('span.now_date').live('click', function() {
		$(this).parent().find('input').val($.format.date(new Date(), "yyyy-MM-dd HH:mm:ss"));
		return false;
    });
	$('span.clear_date').live('click', function() {
		$(this).parent().find('input').val('');
		return false;
    });
// ]]>
</script>


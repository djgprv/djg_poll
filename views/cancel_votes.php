<?php

/**
 * Wolf CMS - Content Management Simplified. <http://www.wolfcms.org>
 * Copyright (C) 2008 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of Wolf CMS.
 *
 * Wolf CMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Wolf CMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Wolf CMS.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Wolf CMS has made an exception to the GNU General Public License for plugins.
 * See exception.txt for details and the full text.
 */

/**
 * The djg_poll plugin

 * @author Michał Uchnast <djgprv@gmail.com>,
 * @copyright kreacjawww.pl
 * @license http://www.gnu.org/licenses/gpl.html GPLv3 license
 */
//print_r($_POST['djg_poll']);
?>
<h1><?php echo __('Cancel the votes'); ?></h1>
<div id="djg_poll">
<form id="djg_poll" action="<?php echo get_url('plugin/djg_poll/cancel_votes'); ?>" method="post">
    <fieldset style="padding: 0.5em;">
        <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
		<input type="hidden" name="djg_poll[id]" value="0" />
		<tr>
			<td class="label"><?php echo __('Question'); ?>:</label></td>
			<td class="field">
				<select id="poll_id" name="djg_poll[poll_id]">
					<?php $current_poll_id = (isset($_POST['djg_poll']['poll_id']) ? $_POST['djg_poll']['poll_id'] : "0"); ?>
					<option name="djg_poll[poll_id]" value="0" <?php if (null == $current_poll_id) echo 'selected = "";'; ?> >&mdash; <?php echo __('Select poll'); ?> &mdash;</option>
					<?php foreach($polls as $poll) : ?>
					<option name="djg_poll[poll_id]" value="<?php echo $poll['pollq_id']; ?>" <?php if ($poll['pollq_id'] == $current_poll_id) echo 'selected = "";'; ?> ><?php echo '('.$poll['pollq_id'].') ' . Djgpoll::trim_text($poll['pollq_question'],40); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
			<td class="help"><?php echo __('You can set the time between successive votes.'); ?></td>
		</tr>
		<tr>
			<td class="label"><?php echo __('Date range'); ?>:</label></td>
			<td class="field">
				<ul class="list">
					<li><button type="button" class="last_hour"><?php echo __('last hour'); ?></button></li>
					<li><button type="button" class="last_day"><?php echo __('last day'); ?></button></li>
					<li><button type="button" class="last_week"><?php echo __('last week'); ?></button></li>
					<li><button type="button" class="last_year"><?php echo __('last year'); ?></button></li>
					<li><button type="button" class="all_votes"><?php echo __('all votes'); ?></button></li>
				</ul>
			</td>
			<td class="help"><?php echo __('Quick choice.'); ?></td>
		</tr>
		<tr>
			<td class="label"><?php echo __('Start'); ?>:</label></td>
			<td class="field">
				<input id="startvote" name="djg_poll[startvote]" type="text" value="<?php if($djg_poll['startvote']) echo $djg_poll['startvote']; ?>" />&emsp;
				<span class="now_date"><?php echo __('now'); ?></span>&emsp;<span class="clear_date"><?php echo __('clear'); ?></span>
			</td>
			<td class="help"><?php echo __('If You don\'t want to use lifetime just leave empty fields.'); ?></td>
		</tr>
		<tr>
			<td class="label"><?php echo __('End'); ?>:</label></td>
			<td class="field">
				<input id="endvote" name="djg_poll[endvote]" type="text" value="<?php if($djg_poll['endvote']) echo $djg_poll['endvote']; ?>" />
				&emsp;<span class="now_date"><?php echo __('now'); ?></span>&emsp;<span class="clear_date"><?php echo __('clear'); ?></span>
			</td>
			<td class="help"><?php echo __('You can use keybord cursor to change value.'); ?></td>
		</tr>
        </table>
    </fieldset>
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Cancel the votes'); ?>" />
    </p>
</form>
</div>              
<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
	$.format = DateFormat.format;
	$.datetimeEntry.setDefaults({spinnerImage: '<?php echo rtrim(URL_PUBLIC,'/').(USE_MOD_REWRITE ? '/': '/?/'); ?>wolf/plugins/djg_poll/js/spinnerDefault.png'});
	if($("#startvote").val()=='0000-00-00 00:00:00'){ $("#startvote").val(''); };
	if($("#endvote").val()=='0000-00-00 00:00:00'){	$("#endvote").val(''); };
	$("#startvote").datetimeEntry({datetimeFormat: 'Y-O-D H:M:S'});
	$("#endvote").datetimeEntry({datetimeFormat: 'Y-O-D H:M:S'});	
	
	$('input').attr('autocomplete','off');
	$(':input').bind('change', function() { setConfirmUnload(true); });
	$('form').submit(function() {$(this).find('.button').remove(); setConfirmUnload(false); return true; });

	/*  date buttons */
	$('button.last_hour').live('click', function() {
		$('body').find('#startvote').val($.format.date(new Date() - 60 * 60 * 1000, "yyyy-MM-dd HH:mm:ss"));
		$('body').find('#endvote').val($.format.date(new Date(), "yyyy-MM-dd HH:mm:ss")); 		
		return true;
    });
	$('button.last_day').live('click', function() {
		$('body').find('#startvote').val($.format.date(new Date() - 60*60*24*1*1000, "yyyy-MM-dd HH:mm:ss")); 
		$('body').find('#endvote').val($.format.date(new Date(), "yyyy-MM-dd HH:mm:ss")); 
		return true;
    });
	$('button.last_week').live('click', function() {
		$('body').find('#startvote').val($.format.date(new Date() - 60*60*24*7*1000, "yyyy-MM-dd HH:mm:ss")); 
		$('body').find('#endvote').val($.format.date(new Date(), "yyyy-MM-dd HH:mm:ss")); 
		return true;
    });	
	$('button.last_year').live('click', function() {
		$('body').find('#startvote').val($.format.date(new Date() - 60*60*24*365*1000, "yyyy-MM-dd HH:mm:ss")); 
		$('body').find('#endvote').val($.format.date(new Date(), "yyyy-MM-dd HH:mm:ss")); 
		return true;
    });
	$('button.all_votes').live('click', function() {
		$('body').find('#startvote').val(''); 
		$('body').find('#endvote').val($.format.date(new Date(), "yyyy-MM-dd HH:mm:ss")); 
		return true;
    });	
	$('span.now_date').live('click', function() {
		$(this).parent().find('input').val($.format.date(new Date(), "yyyy-MM-dd HH:mm:ss")); return false;
    });
	$('span.clear_date').live('click', function() {
		$(this).parent().find('input').val('');	return false;
    });
    function setConfirmUnload(on, msg) {
        window.onbeforeunload = (on) ? unloadMessage : null; return true;
    };
    function unloadMessage() {
        return '<?php echo __('You have modified this page. If you navigate away from this page without first saving your data, the changes will be lost.'); ?>';
    };
});
// ]]>
</script>
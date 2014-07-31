<?php if (!defined('IN_CMS')) { exit(); } ?>

<h1><?php echo __('Statistics'); ?></h1>
<h3><?php echo __('General statistics'); ?></h3>

<table id="djg_poll_list">
	<thead>
		<td><?php echo __('name'); ?></td>
		<td><?php echo __('value'); ?></td>
		<td><?php echo __('name'); ?></td>
		<td><?php echo __('value'); ?></td>
	</thead>
  <tr class="odd">
		<td><?php echo __('Total polls'); ?></td>
		<td><?php echo $qsa[0]['t'] ?></td>
		<td><?php echo __('Total answers'); ?></td>
		<td><?php echo $asa[0]['tAnswares'] ?></td>
  </tr>
  <tr>
		<td><?php echo __('Total votes'); ?></td>
		<td><?php echo $vsa[0]['tVotes'] ?></td>
		<td><?php echo __('Total unique voters'); ?></td>
		<td><?php echo $uvsa[0]['tVoters'] ?></td>
  </tr>
  <tr class="odd">
		<td><?php echo __('Total active'); ?></td>
		<td><?php echo $qsa[0]['tActive'] ?></td>
		<td><?php echo __('Total multiple'); ?></td>
		<td><?php echo $qsa[0]['tMultiple'] ?></td>
  </tr>
	<tbody>
</table>
<h3><?php echo __('Detailed statistics'); ?></h3>
<?php $start_date = (isset($_POST['djg_poll']['start_date'])) ? $_POST['djg_poll']['start_date']:date ( 'Y-m-d', strtotime ( '-1 year' . date('Y-m-d') ) ); ?>
<?php $end_date = (isset($_POST['djg_poll']['end_date'])) ? $_POST['djg_poll']['end_date']:date('Y-m-d'); ?>
<div id="djg_poll">
<form method="POST">
<?php echo __('Start date:');?>
<input id="djg_poll_start_date" maxlength="10" name="djg_poll[start_date]" READONLY size="10" type="text" value="<?php echo substr($start_date, 0, 10); ?>" />
<img class="datepicker" onclick="displayDatePicker('djg_poll[start_date]');" src="<?php echo URI_PUBLIC;?>wolf/admin/images/icon_cal.gif" alt="<?php echo __('Show Calendar'); ?>" />
<?php echo __('End date:');?>
<input id="djg_poll_end_date" maxlength="10" name="djg_poll[end_date]" READONLY size="10" type="text" value="<?php echo substr($end_date, 0, 10); ?>" />
<img class="datepicker" onclick="displayDatePicker('djg_poll[end_date]');" src="<?php echo URI_PUBLIC;?>wolf/admin/images/icon_cal.gif" alt="<?php echo __('Show Calendar'); ?>" />
<?php echo __('Question:');?>
<select id="poll_id" name="djg_poll[poll_id]">
<?php $current_poll_id = (isset($_POST['djg_poll']['poll_id']) ? $_POST['djg_poll']['poll_id'] : "0"); ?>
<option  name="djg_poll[poll_id]" value="0" <?php if (null == $current_poll_id) echo 'selected = "";'; ?> >&mdash; <?php echo __('Select poll'); ?> &mdash;</option>
<?php foreach($polls as $poll) : ?>
<option  name="djg_poll[poll_id]" value="<?php echo $poll['pollq_id']; ?>" <?php if ($poll['pollq_id'] == $current_poll_id) echo 'selected = "";'; ?> ><?php echo '('.$poll['pollq_id'].') ' . Djgpoll::trim_text($poll['pollq_question'],40); ?></option>
<?php endforeach; ?>
</select><button type="submit"><?php echo __('Generate chart'); ?></button>
</form>
</div>
<?php if ($error == 0): ?>
<p><strong><?php echo __('Votes per day'); ?></strong></p>
<img src="<?php echo URL_PUBLIC; ?>djg_poll_chart.php/<?php echo $_POST['djg_poll']['start_date']; ?>/<?php echo $_POST['djg_poll']['end_date']; ?>/<?php echo $_POST['djg_poll']['poll_id']; ?>/votesPerDay"/>
<?php endif; ?>

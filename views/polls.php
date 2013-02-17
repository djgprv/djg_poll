<?php if (!defined('IN_CMS')) { exit(); } ?>
<h1><?php echo __('List of all polls'); ?></h1>
<div style="margin: 1em auto; padding: 0.1em;">
		<?php
		use_helper('Pagination');
		$__CMS_CONN__ = Record::getConnection();
		$pollq = $__CMS_CONN__->query('SELECT pollq_id FROM '.TABLE_PREFIX.'djg_pollsq ');	
		$listLimit = Plugin::getSetting("resultsPerPage","djg_poll");
		$listCount = count($pollq->fetchAll(PDO::FETCH_ASSOC));
		$listOffset = ($page_number-1) * $listLimit;
		($listOffset>0) ? $offsetQuery = ' OFFSET '.$listOffset : $offsetQuery = '';
		
		$pollq = $__CMS_CONN__->query('SELECT *,( pollq_startvote = "0000-00-00 00:00:00" OR pollq_endvote = "0000-00-00 00:00:00" OR (NOW()BETWEEN pollq_startvote AND pollq_endvote) ) as pollq_dead FROM '.TABLE_PREFIX.'djg_pollsq ORDER BY pollq_id DESC LIMIT ' . $listLimit . $offsetQuery);
		$polls = $pollq->fetchAll();
		
		$pagination = new Pagination(array(
		'base_url'		=> get_url('plugin/djg_poll/polls/'),
		'total_rows'	=> $listCount,
		'per_page'      => $listLimit,
		'num_links'     => 3,
		'cur_page'      => $page_number,
		));
		?>
	<div class ="pagination">
		<?php
		echo __('Total: :count polls',array(':count' => $listCount));
		echo $pagination->createLinks();
		?>
	</div>
<table id="djg_poll_list">
	<thead>
		<td class="page_id">id</td>
		<td class="question"><?php echo __('question'); ?></td>
		<td class="date"><?php echo __('date'); ?></td>
		<td class="actions"><?php echo __('actions'); ?></td>
	</thead>
	<tbody>
<?php
	if (count($polls)>0):
	foreach ($polls as $poll):
		$pn = ($page_number!=0)?$page_number:'0';
		$currentStatus = ( (int)$poll['pollq_active'] === 1 )?'16_on.png':'16_off.png';
		$color='';
		/* font color 
		if ( ((int)$poll['pollq_dead'] === 1) && ( (int)$poll['pollq_active'] === 0 ) ):
			$color = 'color:red; font-wedth:bold;';
		elseif ( ((int)$poll['pollq_dead'] === 1) || ( (int)$poll['pollq_active'] === 0 ) ):
			$color = 'color:orange;';
		endif;
		*/
		echo '<tr class="' .  even_odd() . '" style="' . $color  . '">';
		echo '<td class="page_id">' . (int)$poll['pollq_id'] . '</td>';
		echo '<td class="question">' . Djgpoll::trim_text($poll['pollq_question']) .'</td>';
        echo '<td class="date"><span class="date_1">' . $poll['pollq_date'] . '</span><span class="date_2">' . $poll['pollq_date'] . '</span></td>';
		echo '<td class="actions">';
		echo '<div class="actions_wrapper">';
		echo '<a href="' . get_url('plugin/djg_poll/edit') . '/' . (int)$poll['pollq_id'] . '/'.$pn.'"><img src="' . PLUGINS_URI.'djg_poll/images/16_edit.png' . '" title="'.__('edit').'" alt="'.__('edit').'"></a> ';
		echo ( ( (int)$poll['pollq_dead'] ) === 1)?'<img src="' . PLUGINS_URI.'djg_poll/images/16_smile.png' . '" title="'.__('in time').'" alt="'.__('in time').'"> ':'<img src="' . PLUGINS_URI.'djg_poll/images/16_dead.png' . '" title="'.__('beyond the time').'" alt="'.__('beyond the time').'"> ';
		echo'<a href="' . get_url('plugin/djg_poll/onOff') . '/' . (int)$poll['pollq_id'] . '/'.$pn.'"><img src="' . PLUGINS_URI.'djg_poll/images/' . $currentStatus . '" title="'.__('activate / deactivate').'" alt="'.__('activate / deactivate').'"></a> '.
      '<a href="' . get_url('plugin/djg_poll/delete') . '/' . (int)$poll['pollq_id'] . '/'.$pn.'"';
      echo "onclick=\"return confirm('".__('Do you really want to remove this poll?')."')\">";
      echo '<img src="' . PLUGINS_URI.'djg_poll/images/16_del.png' . '" title="'.__('remove').'" alt="'.__('remove').'"></a>'.
			'</div>' .
		    '</td>'; 
		echo '</tr>';
	endforeach;
	else:
		echo '<tr><td colspan="4">' . __('No polls.') . '</td></tr>';
	endif;
?>	
	</tbody>
</table>
	<div class ="pagination">
		<?php
		echo __('Total: :count polls',array(':count' => $listCount));
		echo $pagination->createLinks();
		?>
	</div>
</div>
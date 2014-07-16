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
/**
	* history version
	* 0.31 - fix voting security bug (thx moroz), cleanup the code, small changes in en-message.php
	* 0.0.3 - new trim text fynction, fixed division by zero in renderPollResults  
	* 0.0.2 - clean up, bug fixes, lifetime option, polish translation
	* 0.0.1 - to test
*/
Plugin::setInfos(array(
	'id'		=> 'djg_poll',
	'title'		=> __('[djg] Poll'),
	'description'	=> __('AJAX poll system to your WolfCMS site.'),
	'version'	=> '0.31',
	'license'	=> 'GPL',
	'author'	=> 'Michał Uchnast',
	'website'	=> 'http://www.kreacjawww.pl/',
	'update_url'	=> 'https://raw.githubusercontent.com/djgprv/djg_poll/master/versions.xml',
	'require_wolf_version' => '0.7.3',
	'type'		=> 'both'
));
define('DJG_POLL_ROOT_DIR', CORE_ROOT . '/plugins/djg_poll/');
define('DJG_POLL_DEBUG', true);

Plugin::addController('djg_poll', __('[djg] Poll'), 'administrator', Plugin::getSetting('showTab','djg_poll'));

Plugin::addJavascript('djg_poll', 'js/jquery.datetimeentry.js');
Plugin::addJavascript('djg_poll', 'js/jquery.dateFormat-1.0.js');

include_once('models'.DS.'Djgpoll.php');
include_once('lib'.DS.'phpMyGraph5.0.php');
include_once('lib'.DS.'DateDifference.php');

Dispatcher::addRoute(array(
  '/djg_poll_assets.js' => '/plugin/djg_poll/djg_poll_frontend_assets',
  '/djg_poll_vote.php' => '/plugin/djg_poll/djg_poll_ajax_vote',
  '/djg_poll_chart.php/:any/:any/:num/:any' => '/plugin/djg_poll/djg_poll_chart/$1/$2/$3/$4'
));
function djg_poll_display_by_id($questionId){
	echo '<div class="djg_poll">';
    echo Djgpoll::renderPollForm($questionId);
	echo '</div>';
}
function djg_poll_vote_poll_by_id($questionId){
	$__CMS_CONN__ = Record::getConnection();
	$pollsq = $__CMS_CONN__->query('SELECT pollq_id FROM '.TABLE_PREFIX.'djg_pollsq WHERE pollq_id = '.$questionId);
	$q = $pollsq->fetchAll();
	echo (count($q) > 0)?djg_poll_display_by_id($questionId):'<p>'.__('No polls by id: questionId',array('questionId'=>$questionId)).'</p>';
}
function djg_poll_vote_random_poll(){
	/* no archive - active and live */
	$__CMS_CONN__ = Record::getConnection();
	$pollsq = $__CMS_CONN__->query('SELECT pollq_id FROM '.TABLE_PREFIX.'djg_pollsq WHERE (pollq_active = 1) AND ( pollq_startvote = "0000-00-00 00:00:00" OR pollq_endvote = "0000-00-00 00:00:00" OR (NOW() BETWEEN pollq_startvote AND pollq_endvote) )');
	$q = $pollsq->fetchAll();
	echo (count($q) > 0)?djg_poll_display_by_id($q[rand(0,count($q)-1)]['pollq_id']):'<p>'.__('No polls to display').'</p>';
}
function djg_poll_vote_newest_poll(){
	/* no archive - active and live */
	$__CMS_CONN__ = Record::getConnection();
	$pollsq = $__CMS_CONN__->query('SELECT pollq_id FROM '.TABLE_PREFIX.'djg_pollsq WHERE (pollq_active = 1) AND ( pollq_startvote = "0000-00-00 00:00:00" OR pollq_endvote = "0000-00-00 00:00:00" OR (NOW() BETWEEN pollq_startvote AND pollq_endvote) ) ORDER BY pollq_id DESC LIMIT 1');
	$q = $pollsq->fetchAll();
	echo (count($q) > 0)?djg_poll_display_by_id($q[0]['pollq_id']):'<p>'.__('No polls to vote').'</p>';
}
function djg_poll_show_archive(){
	$__CMS_CONN__ = Record::getConnection();
	$pollsq = $__CMS_CONN__->query('SELECT pollq_id FROM '.TABLE_PREFIX.'djg_pollsq WHERE (pollq_active = 0 OR (pollq_startvote != "0000-00-00 00:00:00" AND pollq_endvote != "0000-00-00 00:00:00" AND (NOW() NOT BETWEEN pollq_startvote AND pollq_endvote)) )  ORDER BY pollq_id DESC');
	$q = $pollsq->fetchAll();
	if (count($q)==0) echo '<p>'.__('No archive polls').'</p>';
	foreach ($q as $row):
		echo Djgpoll::renderPollResults($row['pollq_id']);
	endforeach;
}
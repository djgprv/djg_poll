<?php
if (!defined('IN_CMS')) { exit(); }
	
$PDO = Record::getConnection();
$charset_collate = "DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
$create_table = array();
$create_table['pollsq'] = "CREATE TABLE IF NOT EXISTS ".TABLE_PREFIX."djg_pollsq (".
	"pollq_id int(10) NOT NULL auto_increment,".
	"pollq_question varchar(200) character set utf8 NOT NULL default '',".
	"pollq_timestamp varchar(20) NOT NULL default '',".
	"pollq_totalvotes int(10) NOT NULL default '0',".
	"pollq_active tinyint(1) NOT NULL default '1',".
	"pollq_expiry varchar(20) NOT NULL default '',".
	"pollq_multiple tinyint(3) NOT NULL default '0',".
	"pollq_totalvoters int(10) NOT NULL default '0',".
	"pollq_date datetime NOT NULL,".
	"pollq_startvote datetime NOT NULL,".
	"pollq_endvote datetime NOT NULL,".
	"PRIMARY KEY (pollq_id)) $charset_collate;";
$create_table['pollsa'] = "CREATE TABLE IF NOT EXISTS ".TABLE_PREFIX."djg_pollsa (".
	"polla_aid int(10) NOT NULL auto_increment,".
	"polla_qid int(10) NOT NULL default '0',".
	"polla_answers varchar(200) character set utf8 NOT NULL default '',".
	"polla_votes int(10) NOT NULL default '0',".
	"PRIMARY KEY (polla_aid)) $charset_collate;";
$create_table['pollsip'] = "CREATE TABLE IF NOT EXISTS ".TABLE_PREFIX."djg_pollsip (".
	"pollip_id int(10) NOT NULL auto_increment,".
	"pollip_qid varchar(10) NOT NULL default '',".
	"pollip_aid varchar(10) NOT NULL default '',".
	"pollip_ip varchar(100) NOT NULL default '',".
	"pollip_host VARCHAR(200) NOT NULL default '',".
	"pollip_timestamp varchar(20) NOT NULL default '0000-00-00 00:00:00',".
	"pollip_user tinytext NOT NULL,".
	"pollip_userid int(10) NOT NULL default '0',".
	"PRIMARY KEY (pollip_id),".
	"KEY pollip_ip (pollip_id),".
	"KEY pollip_qid (pollip_qid)".
	") $charset_collate;";
$stmt = $PDO->prepare($create_table['pollsq']); $stmt->execute();
$stmt = $PDO->prepare($create_table['pollsa']); $stmt->execute();
$stmt = $PDO->prepare($create_table['pollsip']); $stmt->execute();
	
/* example data */

$PDO->exec("INSERT INTO ".TABLE_PREFIX."djg_pollsq (pollq_id, pollq_question, pollq_timestamp, pollq_totalvotes, pollq_active, pollq_expiry, pollq_multiple, pollq_totalvoters, pollq_date, pollq_startvote, pollq_endvote) VALUES 
(1, 'What is your favourite CMS system?', '1', 0, 0, '', 0, 0, '2012-07-31 13:57:05', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'How many unique visitors do you receive daily?', '1', 0, 0, '', 0, 0, '2012-08-03 10:11:45', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Which editor do you use to create your website?', '0', 0, 0, '', 1, 0, '2012-08-03 10:12:49', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'What is most important when it comes to web polls?', '0', 0, 0, '', 1, 0, '2012-08-03 10:13:56', '0000-00-00 00:00:00', '0000-00-00 00:00:00')");
$PDO->exec("INSERT INTO ".TABLE_PREFIX."djg_pollsa (polla_aid, polla_qid, polla_answers, polla_votes) VALUES (1, 3, 'Homesite', 0),(2, 3, 'FrontPage', 0),(3, 3, 'Dreamweaver', 0),(4, 1, 'Joomla', 0),(5, 1, 'Other', 0),(6, 3, 'Text Editor', 0),(7, 3, 'GoLive', 0),(8, 1, 'Wordpress', 0),(9, 1, 'Drupal', 0),(10, 1, 'WolfCMS', 0),(11, 2, '+900', 0),(12, 2, '300 to 600', 0),(13, 2, '600 to 900', 0),(14, 2, '100 to 300', 0),(15, 2, '0 to 100', 0),(16, 3, 'Other', 0),(17, 4, 'Not possible to cheat', 0),(18, 4, 'Easy to setup', 0),(19, 4, 'Appearance', 0),(20, 4, 'The possibility to comment the poll', 0)");	

$settings = array(
	'ver' => '0.0.3',
	'defaultMultiple' => '0',
	'defaultActive' => '1',
	'resultsPerPage' => '5',
	'showTab' => '1',
	'specifyYourVote' => '1',
	'sortResults' => '1',
	'allowSelectAll' => '0',
	'checkCookie' => '0',
	'checkIP' => '0',
	'chartsSize' => '500x220'
);

if (Plugin::setAllSettings($settings, 'djg_poll'))
	Flash::setNow('success', __('djg_poll - plugin settings initialized.'));
else
	Flash::setNow('error', __('djg_poll - unable to store plugin settings!'));	
exit();
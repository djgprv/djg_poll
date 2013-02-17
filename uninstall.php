<?php
if (!defined('IN_CMS')) { exit(); }

$PDO = Record::getConnection();
$error = 0;
if($PDO->exec('DROP TABLE '.TABLE_PREFIX.'djg_pollsq') === false) $error ++;
if($PDO->exec('DROP TABLE '.TABLE_PREFIX.'djg_pollsa') === false) $error ++;
if($PDO->exec('DROP TABLE '.TABLE_PREFIX.'djg_pollsip') === false) $error ++;

if( ($error > 0) || (Plugin::deleteAllSettings('djg_poll') === false) )
  Flash::set('error', __('Unable to uninstall djg_poll plugin.'));
else
  Flash::set('success', __('Plugin succesfully uninstalled!')); 
exit();
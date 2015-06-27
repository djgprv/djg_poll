[djg] Poll
----
* Adds an AJAX poll system to your WolfCMS site. You can also easily add a poll into your WolfCMS's page.

HISTORY VERSION
----
* **0.35**
* fix dateFormat jQuery plugin bug
* **0.34**
* added cancel votes functionality
* editable usleep and cookie prefix variables
* cleanup the code
* dba tables structure has bean changed
* small changes in en-message.php
* update icons
* added "total unique voters" in statistics
* tested in WolfCMS 0.8.0
* **0.31 - 0.33**
* fix voting security bug (thx moroz)
* cleanup the code
* small changes in en-message.php
* **0.0.3**
* new trim text fynction 
* fixed division by zero in renderPollResults
* **0.0.2**
* clean up, bug fixes
* lifetime option
* polish translation
* **0.0.1**
* beta

TO DO
----
* images as unsware
* easly styling poll

HOW TO INSTALL
----
* Copy files to wolf/plugins/djg_poll directory
* Enter the admin page installation and activate the plugin.
* Append to layout:
* &lt;!-- djg_poll --&gt;&lt;link type="text/css" href="&lt;?php echo URL_PUBLIC; ?&gt;wolf/plugins/djg_poll/assets/djg_poll_frontend.css" rel="stylesheet" /&gt;&lt;script type="text/javascript" src="&lt;?php echo URL_PUBLIC; ?&gt;djg_poll_assets.js"&gt;&lt;/script&gt;&lt;!-- end djg_poll --&gt;

* Required - <i style="color:red;">jQuery 1.7.2 in fronted</i> or higher.
* **offline**
* &lt;script type="text/javascript" src="<?php echo URL_PUBLIC; ?>wolf/plugins/djg_poll/assets/jquery-1.7.2.min.js"&gt;&lt;/script&gt;
* **online**
* &lt;script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js" &gt;&lt;/script&gt;

EXAMPLE USE
----
* Insert to page content or sidebar:</li>
* <code><span style="color:red;">&lt;?php</span> if (Plugin::isEnabled('djg_poll')) djg_poll_vote_newest_poll(); <span style="color:red;">?&gt;</span></code>
* <code><span style="color:red;">&lt;?php</span> if (Plugin::isEnabled('djg_poll')) djg_poll_vote_poll_by_id(1); <span style="color:red;">?&gt;</span></code>
* <code><span style="color:red;">&lt;?php</span> if (Plugin::isEnabled('djg_poll')) djg_poll_vote_random_poll(); <span style="color:red;">?&gt;</span></code>
* <code><span style="color:red;">&lt;?php</span> if (Plugin::isEnabled('djg_poll')) djg_poll_show_archive(); <span style="color:red;">?&gt;</span></code>

ICONS
----
http://www.iconfinder.com/search/?q=iconset%3Afatcow

License
----
MIT
<?php if (!defined('IN_CMS')) { exit(); } ?>
<h1><?php echo __('Documentation'); ?></h1>

<h3>How to install</h3>
<ol style="list-style: decimal inside none;">
<li>Copy files to wolf/plugins/djg_poll directory</li>
<li>Enter the admin page installation and activate the plugin.</li>
<li>Append to layout:<br/>
<code>&lt;!-- djg_poll --&gt;&lt;link type="text/css" href="&lt;?php echo URL_PUBLIC; ?&gt;wolf/plugins/djg_poll/assets/djg_poll_frontend.css" rel="stylesheet" /&gt;&lt;script type="text/javascript" src="&lt;?php echo URL_PUBLIC; ?&gt;djg_poll_assets.js"&gt;&lt;/script&gt;&lt;!-- end djg_poll --&gt;</code>
</ol>
<p>Required - <i style="color:red;">jQuery 1.7.2 in fronted</i> or higher.<br />
offline - <code>&lt;script type="text/javascript" src="<?php echo URL_PUBLIC; ?>wolf/plugins/djg_poll/assets/jquery-1.7.2.min.js"&gt;&lt;/script&gt;</code><br />
online - <code>&lt;script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"&gt;&lt;/script&gt;</code></p>

<h3>Example use</h3>
<ul style="list-style: square inside none;">
<li>Insert to page content or sidebar:</li>
<li><code><span style="color:red;">&lt;?php</span> if (Plugin::isEnabled('djg_poll')) djg_poll_vote_newest_poll(); <span style="color:red;">?&gt;</span></code></li>
<li><code><span style="color:red;">&lt;?php</span> if (Plugin::isEnabled('djg_poll')) djg_poll_vote_poll_by_id(1); <span style="color:red;">?&gt;</span></code></li>
<li><code><span style="color:red;">&lt;?php</span> if (Plugin::isEnabled('djg_poll')) djg_poll_vote_random_poll(); <span style="color:red;">?&gt;</span></code></li>
<li><code><span style="color:red;">&lt;?php</span> if (Plugin::isEnabled('djg_poll')) djg_poll_show_archive(); <span style="color:red;">?&gt;</span></code></li>
</ul>

<h3>History version</h3>
<ul style="list-style: square inside none;">
<li>0.0.3 - new trim text fynction, fixed division by zero in renderPollResults</li>
<li>0.0.2 - clean up, bug fixes, lifetime option, polish translation</li>
<li>0.0.1 - beta</li>
</ul>

<h3>Translations</h3>
<ul style="list-style: square inside none;">
<li>who is interested in? ;)</li>
</ul>

<h3>Used in plugin</h3>
<ul style="list-style: square inside none;">
<li>icons - http://www.iconfinder.com/search/?q=iconset%3Afatcow</li>
</ul>
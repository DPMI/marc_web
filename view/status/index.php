<?php /* -*- mode: html; -*- */ ?>
<h1>Measurement Area Control Daemon</h1>

<h2>Version</h2>
<table class="status" cellspacing="0">
	<tr><th>&nbsp;</th><th>WebGUI</th><th>MArCd</th></tr>
	<tr><td>Software</td><td><?=$version?></td><td><?=$marcd['version']?></td></tr>
	<tr><td>VCS</td><td><?=$vcs != '' ? $vcs : '<span class="na">No</span>'?></td><td><?=$marcd['vcs'] != '' ? $marcd['vcs'] : '<span class="na">No</span>'?></td></tr>
	<tr><td>Database</td><td><?=$dbversion?></td><td><span class="na">N/A</span></td></tr>
	<tr><td>Caputils</td><td><span class="na">N/A</span></td><td><?=$marcd['caputils']?></td></tr>
</table>

<h2>Database</h2>
<table class="status" cellspacing="0">
	<tr><td>Hostname</td><td><?=$DB_SERVER?></td></tr>
	<tr><td>Username</td><td><?=$user?> (Using password: <?=$password == '' ? 'No' : 'Yes'?>)</td></tr>
	<tr><td>Database</td><td><?=$DATABASE?></td></tr>
</table>

<h2>Paths</h2>
<table class="status" cellspacing="0">
	<tr><td>Prefix</td><td><?=check_path($prefix, 'rd', $message)?></td><td><?=$message?></td></tr>
	<tr><td>Sysconfdir</td><td><?=check_path($sysconfdir, 'rd', $message)?></td><td><?=$message?></td></tr>
	<tr><td>Localstatedir</td><td><?=check_path($localstatedir, 'rd', $message)?></td><td><?=$message?></td></tr>
	<tr><td>rrdbase</td><td><?=check_path($rrdbase, 'rwd', $message, $usergroup)?></td><td><?=$message?></td></tr>
	<tr><td>rrdtool</td><td><?=check_path($rrdtool, 'rx', $message)?></td><td><?=$message?></td></tr>
</table>

<h2>Permissions</h2>
<table class="status" cellspacing="0">
	<tr><td>User</td><td><?=$apache_user?></td><td></td></tr>
	<tr><td>Usergroup</td><td><?=check_group($usergroup['name'], $message)?></td><td><?=$message?></td></tr>
</table>

<h2>URL</h2>
<table class="status" cellspacing="0">
	<tr><td>Root</td><td><?=$root?></td></tr>
	<tr><td>Index</td><td><?=$index?></td></tr>
</table>

<h2>Settings</h2>
<table class="status" cellspacing="0">
	<tr><td>Title</td><td><?=$title?></td></tr>
	<tr><td>Subtitle</td><td><?=isset($subtitle) ? $subtitle : '<span class="na">(unset)</span>'?></td></tr>
	<tr><td>Ping</td><td><?=$use_ping ? 'Enabled' : 'Disabled' ?></td></tr>
	<tr><td>MP Timeout</td><td><?=$mp_timeout?></td></tr>
</table>

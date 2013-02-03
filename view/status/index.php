<?php /* -*- mode: html; -*- */ ?>
<h1>Measurement Area Control Daemon</h1>

<h2>Configuration</h2>
<table class="list" width="40%">
	<tr><th colspan="2">Version</th></tr>
	<tr><th width="100">Software</th><td><?=$version?></td></tr>
	<tr><th>VCS</th><td><?=$vcs != '' ? $vcs : 'No'?></td></tr>
	<tr><th>Database</th><td><?=$dbversion?></td></tr>

	<tr><th colspan="2">Database</th></tr>
	<tr><th>Hostname</th><td><?=$DB_SERVER?></td></tr>
	<tr><th>Username</th><td><?=$user?> (Using password: <?=$password == '' ? 'No' : 'Yes'?>)</td></tr>
	<tr><th>Database</th><td><?=$DATABASE?></td></tr>

	<tr><th colspan="2">Paths</th></tr>
	<tr><th>Prefix</th><td><?=$prefix?></td></tr>
	<tr><th>Sysconfdir</th><td><?=$sysconfdir?></td></tr>
	<tr><th>Localstatedir</th><td><?=$localstatedir?></td></tr>
	<tr><th>rrdbase</th><td><?=$rrdbase?></td></tr>

	<tr><th colspan="2">Permissions</th></tr>
	<tr><th>Usergroup</th><td><?=$usergroup['name']?></td></tr>

	<tr><th colspan="2">URL</th></tr>
	<tr><th>Root</th><td><?=$root?></td></tr>
	<tr><th>Index</th><td><?=$index?></td></tr>

	<tr><th colspan="2">Settings</th></tr>
	<tr><th>Title</th><td><?=$title?></td></tr>
	<tr><th>Subtitle</th><td><?=isset($subtitle) ? $subtitle : '(unset)'?></td></tr>
</table>

<h2>MArCd</h2>
<table class="list" width="40%">
	<tr><th colspan="2">Version</th></tr>
	<tr><th width="100">Software</th><td><?=$marcd['version']?></td></tr>
	<tr><th>VCS</th><td><?=$marcd['vcs'] != '' ? $marcd['vcs'] : 'No'?></td></tr>
	<tr><th>caputils</th><td><?=$marcd['caputils']?></td></tr>
</table>

<?php
	if(defined('PAGEINC')) {
		if($logged == true) {
			echo '<h1>Logs</h1>
			<h3>300 last lines</h3>';
			require('classes/sampssh.class.php');
			$sampSSH = new SampSSH($serverconfig['sship'], $serverconfig['sshport'], $serverconfig['sshuser'], $serverconfig['sshpasswd'], $serverconfig['serverdir'], $serverconfig['serverexe']);
			$loglines = $sampSSH->getLastLogLines(300);
			echo nl2br(htmlspecialchars($loglines));
		}
		else {
			header('Location: ' . $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'login'));
		}
	}
?>
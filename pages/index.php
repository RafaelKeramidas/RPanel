<?php
	if(defined('PAGEINC')) {
		if($logged == true) {
			echo '<h1>Home</h1>';
			
			require('classes/sampssh.class.php');
			$sampSSH = new SampSSH($serverconfig['sship'], $serverconfig['sshport'], $serverconfig['sshuser'], $serverconfig['sshpasswd'], $serverconfig['serverdir'], $serverconfig['serverexe']);
			if(isset($_POST['start'])) {
				$sampSSH->startServer();
				sleep(1);
				header('Location: ' . $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'index'));
			}
			
			if(isset($_POST['stop'])) {
				$sampSSH->stopServer();
				sleep(1);
				header('Location: ' . $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'index'));
			}
			
			if(isset($_POST['restart'])) {
				$sampSSH->restartServer();
				sleep(1);
				header('Location: ' . $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'index'));
			}
			
			if($sampQuery->isOnline()) {
				echo '<h3>Server on</h3>
				<form action="' . $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'index') . '" method="POST">
					<input type="submit" name="restart" value="Restart" class="button" /> 
					<input type="submit" name="stop" value="Stop" class="button" />
				</form>';
			}
			else {
				echo '<h3>Server off</h3>
				<form action="' . $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'index') . '" method="POST">
					<input type="submit" name="start" value="Start" class="button" />
				</form>';
			}
		}
		else {
			header('Location: ' . $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'login'));
		}
	}
?>
<?php
	if(defined('PAGEINC')) {
		if($logged == true) {
			echo '<h1>RCON</h1>';
			
			if(isset($_POST['cmd'])) {
				require('classes/sampssh.class.php');
				require('classes/samprcon.class.php');
				$sampSSH = new SampSSH($serverconfig['sship'], $serverconfig['sshport'], $serverconfig['sshuser'], $serverconfig['sshpasswd'], $serverconfig['serverdir'], $serverconfig['serverexe']);
				$rconPasswd = $sampSSH->getRcon();
				$sampRcon = new SampRcon($serverconfig['sampip'], $serverconfig['sampport'], $rconPasswd);
				$sampRcon->sendRcon($_POST['cmd']);
				echo '<p>Command sent.</p>';
			}
			if($sampQuery->isOnline()) {
				?>
				<form action="<?php echo $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'rcon'); ?>" method="POST">
					<p>Command: <input type="text" name="cmd" class="field" size=55 /> <input type="submit" value="Sent" class="button" /></p>
				</form>
				<?php
			}
			else {
				echo '<h3>Server off</h3>';
			}
		}
		else {
			header('Location: ' . $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'login'));
		}
	}
?>
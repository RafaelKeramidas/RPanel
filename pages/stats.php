<?php
	if(defined('PAGEINC')) {
		if($logged == true) {
			echo '<h1>Stats</h1>';
			if(!$sampQuery->isOnline()) {
				echo '<h3>Server off</h3>';
			}
			else {
				$srules = $sampQuery->getRules();
				$splayers = $sampQuery->getDetailedPlayers();
				$usage = round(((100 * $sinfos['players'])/$sinfos['maxplayers']), 1);
				$password = $sinfos['password'] ? 'Yes' : 'No';
				
				echo '<h3>Server infos</h3>
				<div id="tablediv">
					<table>
						<tr><td><b>Hostname</b></td><td>' . $sinfos['hostname'] . '</td></tr>
						<tr><td><b>IP:Port</b></td><td>' . $serverconfig['sampip'] . ':' . $serverconfig['sampport'] . '</td></tr>
						<tr><td><b>Players</b></td><td>' . $sinfos['players'] . '/' . $sinfos['maxplayers'] . ' (' . $usage . '%)</td></tr>
						<tr><td><b>Password</b></td><td>' . $password . '</td></tr>
						<tr><td><b>Gamemode</b></td><td>' . $sinfos['gamemode'] . '</td></tr>
						<tr><td><b>Mapname</b></td><td>' . $sinfos['mapname'] . '</td></tr>
						<tr><td><b>Gravity</b></td><td>' . $srules['gravity'] . '</td></tr>
						<tr><td><b>Weather</b></td><td>' . $srules['weather'] . '</td></tr>
						<tr><td><b>Worldtime</b></td><td>' . $srules['worldtime'] . '</td></tr>
						<tr><td><b>Version</b></td><td>' . $srules['version'] . '</td></tr>
						<tr><td><b>Website</b></td><td>' . $srules['weburl'] . '</td></tr>
					</table>
				</div>
				<h3>Players</h3>';
				if($sinfos['players'] > 0) {
					echo '<div id="tablediv">
					<table>
					<tr><th>ID</th><th>Nickname</th><th>Score</th><th>Ping</th></tr>';
					foreach($splayers as $players) {
						echo '<tr><td>' . $players['playerid'] . '</td><td>' . $players['nickname'] . '</td><td>' . $players['score'] . '</td><td>' . $players['ping'] . '</td></tr>';
					}
					echo '</table>
					</div>';
				}
				else {
					echo '<b>No players...</b>';
				}
			}
		}
		else {
			header('Location: ' . $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'login'));
		}
	}
?>
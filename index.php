<?php
	/***
	 * RPanel - Main
	 * 
	 * @Author		Rafael 'R@f' Keramidas <rafael@keramid.as>
	 * @Version		1.0.0
	 * @Date		08th Mai 2012
	 * @Licence		GPLv3 <Look at GPLv3.txt>
	 ***/
	
	session_start();
	
	/* Page include define */
	define('PAGEINC', true);
	
	/* Config file */
	require('includes/config.inc.php');
	
	/* Logged variable */
	$logged = false;
	if(isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
		$logged = true;
	}
	
	/* Check if HTTPS is enabled */
	if($mainconfig['httpsonly'] == true && $_SERVER['HTTPS'] == false)
		header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		
	/* Classes */
	require('classes/panelfunc.class.php');
	require('classes/sampquery.class.php');
	
	$panelFunc = new PanelFunctions();
	$sampQuery = new SampQuery($serverconfig['sampip'], $serverconfig['sampport']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<title><?php echo $mainconfig['paneltitle']; ?> - RPanel</title>
        <link rel="stylesheet" href="style.css" />
	</head>
	<body>
		<div id="page">
			<div id="header">
				<div id="logo">
					<img src="images/logo.png" alt="Logo" />
				</div>
				<div id="srvstatus">
					<?php
						if($sampQuery->isOnline()) {
							$sinfos = $sampQuery->getInfo();
							echo '<p class="srvon">' . $sinfos['players'] . '/' . $sinfos['maxplayers'] . ' Players</p>';
						}
						else {
							echo '<p class="srvoff">Server off</p>';
						}
					?>
				</div>
			</div>
			<div id="main">
				<div id="menu">
					<ul>
						<li><a href="<?php echo $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'index'); ?>">Home</a></li>
						<li><a href="<?php echo $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'stats'); ?>">Stats</a></li>
						<li><a href="<?php echo $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'rcon'); ?>">RCON</a></li>
						<li><a href="<?php echo $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'logs'); ?>">Logs</a></li>
						<li><a href="<?php echo $panelFunc->rewriteURL($mainconfig['urlrewrite'], 'logout'); ?>">Disconnect</a></li>
					</ul>
				</div>
				<div id="content">
					<?php
						if(!isset($_GET['p'])) 
							$_GET['p'] = 'index';
					
						if(!file_exists('pages/'.$_GET['p'].'.php')) 
							$_GET['p'] = '404';
							
						include('pages/'.$_GET['p'].'.php');
					?>
				</div>
				<div id="footer">
					<p id="copy">RPanel - Designed and developped by <a href="http://rafael.keramid.as" target="_blank">Rafael Keramidas</a>.</p>
				</div>
		</div>
			</div>
			
	</body>
</html>
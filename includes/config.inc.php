<?php
	/***
	 * RPanel config file
	 ***/
	 
	$mainconfig = array(
		'paneltitle' => 'My panel', /* For the title */
		'username' => 'User', /* Panel login username */
		'password' => '', /* SHA1 Hashed password */
		'httpsonly' => false, /* Force HTTPS (change to true to activate) */
		'urlrewrite' => false, /* URL Rewriting (change to true to activate) */
		'version' => '1.0.0' /* Version of the panel */
	);
	 
	$serverconfig = array(
		'sampip' => '123.123.123.123', /* SA-MP Server IP */
		'sampport' => 7777, /* SA-MP Server port */
		'sship' => '10.0.0.2', /* IP of the SSH Server */
		'sshport' => 22, /* Port of the SSH Server */
		'sshuser' => 'samp', /* SSH User */
		'sshpasswd' => 'iliketurtles', /* SSH Password for the given user */
		'serverdir' => '~', /* Directory where your SA-MP Server is located */
		'serverexe' => 'samp03svr' /* Name of your SA-MP Server executable */
	);
?>
<?php
	/***
	 * RPanel - SA-MP SSH Class
	 * 
	 * @Author		Rafael 'R@f' Keramidas <rafael@keramid.as>
	 * @Version		1.0
	 * @Date		18th Mai 2012
	 * @Licence		GPLv3 
	 ***/
	
	class SampSSH {
	
		/**
		 * Variables 
		 **/
		private 
			$connexion = null,	
			$serverinfo = array(
				'ip' => null,
				'port' => null,
				'user' => null,
				'passwd' => null,
				'directory' => null,
				'executable' => null
			);
		
		/***
		 * Constructor
		 *
		 * @param	(string)$srvip		SSH IP
		 * @param	(int)$srvport		SSH Port
		 * @param	(string)$srvuser	SSH User
		 * @param	(string)$srvpasswd	SSH Password
		 * @param	(string)$srvdir		SA-MP Server directory 
		 * @param	(string)$srvexe		SA-MP Server executable name
		 * return	boolean				True if the connexion is sucessful, false if not.
		 ***/
		public function __construct($srvip = '127.0.0.1', $srvport = 22, $srvuser = '', $srvpasswd = '', $srvdir = '', $srvexe = '') {
			$this->serverinfo['ip'] = $srvip;
			$this->serverinfo['port'] = $srvport;
			$this->serverinfo['user'] = $srvuser;
			$this->serverinfo['passwd'] = $srvpasswd;
			$this->serverinfo['directory'] = $srvdir;
			$this->serverinfo['executable'] = $srvexe;
			
			if (!($this->connexion = ssh2_connect($this->serverinfo['ip'], $this->serverinfo['port']))) {
				return false;
			} 
			else {
				if (!ssh2_auth_password($this->connexion, $this->serverinfo['user'], $this->serverinfo['passwd'])) {
					return false;
				} 
				else { 
					return true;
				}
			}
		}
		
		/***
		 *
		 * @ingnore
		 *
		 ***/
		private function __invoke($command) {
			$stream = ssh2_exec($this->connexion, 'cd ' . $this->serverinfo['directory'] . ' && ' . $command);
			stream_set_blocking($stream, true);
			$output = stream_get_contents($stream);
			fclose($stream);
			
			return $output;
		}
		
		/***
		 * Checks if the server process is running or not
		 * 
		 * @param	none
		 * @return	boolean		True if the server is running, false if not.
		 ***/
		public function isRunning() {
			$result = $this('ps u -C ' . $this->serverinfo['executable'] . ' | grep -vc USER');
			if($result == 0) 
				return false;
			else
				return true;
		}
		
		/***
		 * Starts the server
		 * 
		 * @param	none
		 * @return	boolean		True if the server was started, false if not (process already running).
		 ***/
		public function startServer() {
			if($this->isRunning()) 
				return false;
				
			$this('screen -dmS SAMP ./' . $this->serverinfo['executable']);
			return true;
		}
		
		/***
		 * Stops the server
		 * 
		 * @param	none
		 * @return	nothing
		 ***/
		public function stopServer() {
			$this('killall ./' . $this->serverinfo['executable']);
		}
		
		/***
		 * Restarts the server
		 * 
		 * @param	none
		 * @return	nothing
		 ***/
		public function restartServer() {
			$this->stopServer();
			sleep(1);
			$this->startserver();
		}
		
		/***
		 * Get the RCON Password from the server.cfg file
		 * 
		 * @param	none
		 * @return	string		RCON Password.
		 ***/
		public function getRcon() {
			$line = $this('cat server.cfg | grep rcon_password');
			$password = substr($line, 14);
			return substr($password, 0, -1);
		}
		
		/***
		 * Get a given amount of lines from the logs.
		 * 
		 * @param	(int)$lines	Number of lines to get from the logs.
		 * @return	string		Log lines
		 ***/
		public function getLastLogLines($lines) {
			$line = $this('tail -n ' . $lines . ' server_log.txt');
			$logs = $line;
			return $logs;
		}
	}
	
	
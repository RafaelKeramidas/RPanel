<?php
	/***
	 * RPanel - SA-MP RCON Class
	 * 
	 * @Author		Rafael 'R@f' Keramidas <rafael@keramid.as>
	 * @Version		1.1
	 * @Date		22th Mai 2012
	 * @Licence		GPLv3 
	 ***/
	
	class SampRcon {
	
		/**
		 * Variables 
		 **/
		private 
			$socket = null,			/* Socket */ 
			$serverinfo = array(	/* Server Infos array */
				'ip' => null,
				'port' => null,
				'passwd' => null,
				'online' => null
			);
		
		/***
		 * Constructor
		 *
		 * @param	(string)$srvip		Server IP
		 * @param	(int)$srvport		Server Port
		 * @param	(string)$srvpass	Server RCON Password
		 * return	void
		 ***/
		public function __construct($srvip = '127.0.0.1', $srvport = 7777, $srvpass = '') {
			$this->serverinfo['ip'] = $srvip;
			$this->serverinfo['port'] = $srvport;
			$this->serverinfo['passwd'] = $srvpass;
			
			$this->socket = fsockopen('udp://' . $this->serverinfo['ip'], $this->serverinfo['port']);
			socket_set_timeout($this->socket, 1);
			
			if($this->socket == false) {
				$this->serverinfo['online'] = false;
				return;
			}
			
			$this->sendPacket('p1234');
			fread($this->socket, 10);
			if(fread($this->socket, 5) != 'p1234') {
				$this->serverinfo['online'] = false;
				return;
			}
			
			$this->serverinfo['online'] = true;
		}
		
		/***
		 * Check if the server is online or not
		 *
		 * @param 	none
		 * @return	boolean		True if the server is on, false if not.
		 ***/
		public function isOnline() {
			return $this->serverinfo['online'];
		}
		
		/***
		 * Send the RCON Command
		 *
		 * @param 	(string)$command	RCON Command to send to the server.
		 * @return	nothing
		 ***/
		public function sendRcon($command) {
			$this->sendPacket('x', $command);
			return fread($this->socket, 100);
		}
		
		/***
		 *
		 * @ingnore
		 *
		 ***/
		private function sendPacket($queryParam, $command = '') {
			$packet  = 'SAMP';
			$packet .= chr(strtok($this->serverinfo['ip'], '.'));
			$packet .= chr(strtok('.')).chr(strtok('.')).chr(strtok('.'));
			$packet .= chr($this->serverinfo['port'] & 0xFF);
			$packet .= chr($this->serverinfo['port'] >> 8 & 0xFF);
			$packet .= $queryParam;
			
			if($queryParam == 'x') {
				$packet .= chr(strlen($this->serverinfo['passwd']) & 0xFF);
				$packet .= chr(strlen($this->serverinfo['passwd']) >> 8 & 0xFF);
				$packet .= $this->serverinfo['passwd'];
				$packet .= chr(strlen($command) & 0xFF);
				$packet .= chr(strlen($command) >> 8 & 0xFF);
				$packet .= $command;
			}
		
			return fwrite($this->socket, $packet); 
		}
	}
	
	
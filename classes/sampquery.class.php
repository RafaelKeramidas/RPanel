<?php
	/***
	 * RPanel - SA-MP Query Class
	 * 
	 * @Author		Rafael 'R@f' Keramidas <rafael@keramid.as>
	 * @Version		1.1
	 * @Date		22th Mai 2012
	 * @Licence		GPLv3 
	 ***/
	
	class SampQuery {
	
		/**
		 * Variables 
		 **/
		private 
			$socket = null,			/* Socket */ 
			$serverinfo = array(	/* Server Infos array */
				'ip' => null,
				'port' => null,
				'online' => null
			);
		
		/***
		 * Constructor
		 *
		 * @param	(string)$srvip		Server IP
		 * @param	(int)$srvport		Server Port
		 * return	void
		 ***/
		public function __construct($srvip = '127.0.0.1', $srvport = 7777) {
			$this->serverinfo['ip'] = $srvip;
			$this->serverinfo['port'] = $srvport;
			
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
		 * Get the server infos (amout of players, hostname, ...)
		 *
		 * @param 	none
		 * @return	array		Array with the server infos. Example :
		 * array
		 *	  'password' => int 0
		 *	  'players' => int 3
		 *	  'maxplayers' => int 200
		 *	  'hostname' => string '[FR/EN] Lalu Stunt - Races/Ladder/More - NO CHEAT ' (length=50)
		 *	  'gamemode' => string 'Lalu's Stunt 4.0.7.3' (length=20)
		 *	  'mapname' => string 'San Andreas' (length=11)
		 ***/
		public function getInfo() {
			$this->sendPacket('i');
			
			fread($this->socket, 11);
			$srvinfos = array(
				'password' => ord(fread($this->socket, 1)),
				'players' => $this->toInteger(fread($this->socket, 2)),
				'maxplayers' => $this->toInteger(fread($this->socket, 2)),
				'hostname' => $this->getPacket(4),
				'gamemode' => $this->getPacket(4),
				'mapname' => $this->getPacket(4)
			);
			
			return $srvinfos;
		}
		
		/***
		 * Get the server rules (website, weather, world time, ...)
		 *
		 * @param 	none
		 * @return	array		Array with the server rules. Example :
		 * array
		 *	  'gravity' => string '0.008' (length=5)
		 *	  'mapname' => string 'San Andreas' (length=11)
		 *	  'version' => string '0.3e' (length=4)
		 *	  'weather' => string '1' (length=1)
		 *	  'weburl' => string 'GtaOnline.fr' (length=12)
		 *	  'worldtime' => string '12:00' (length=5)
		 ***/
		public function getRules() {
			$this->sendPacket('r');
			
			fread($this->socket, 11);
			$rulesCount = ord(fread($this->socket, 2));
			for($i = 0; $i < $rulesCount; $i++) {
				$ruleName = $this->getPacket(1);
				$rules[$ruleName] = $this->getPacket(1);
			}
			
			return $rules;
		}
		
		/***
		 * Get the basic player infos (nickname and score).
		 *
		 * @param 	none
		 * @return	array		Array with the player infos (basic). Example :
		 * array
		 *	  0 => 
		 *		array
		 *		  'nickname' => string 'Roberto_Gaio' (length=12)
		 *		  'score' => int 0
		 *	  1 => 
		 *		array
		 *		  'nickname' => string 'Tej_Parker' (length=10)
		 *		  'score' => int 0
		 *	  2 => 
		 *		array
		 *		  'nickname' => string '[PLP]Interceptor' (length=16)
		 *		  'score' => int 105
		 ***/
		public function getBasicPlayers() {
			$players = array();
			
			$this->sendPacket('c');
			
			fread($this->socket, 11);
			$playerCount = ord(fread($this->socket, 2));
			if($playerCount > 0)
			{
				for($i = 0; $i < $playerCount; $i++)
				{
					$players[] = array (
						'nickname' => $this->getPacket(1),
						'score' => $this->toInteger(fread($this->socket, 4))
					);
				}
			}
			return $players;
		}
		
		/***
		 * Get the detailed player infos (id, nickname, score and ping).
		 *
		 * @param 	none
		 * @return	array		Array with the player infos (detailed). Example :
		 * array
		 *	  0 => 
		 *		array
		 *		  'playerid' => int 0
		 *		  'nickname' => string 'Roberto_Gaio' (length=12)
		 *		  'score' => int 0
		 *		  'ping' => int 72
		 *	  1 => 
		 *		array
		 *		  'playerid' => int 1
		 *		  'nickname' => string 'Tej_Parker' (length=10)
		 *		  'score' => int 0
		 *		  'ping' => int 55
		 *	  2 => 
		 *		array
		 *		  'playerid' => int 2
		 *		  'nickname' => string '[PLP]Interceptor' (length=16)
		 *		  'score' => int 105
		 *		  'ping' => int 55
		 ***/
		public function getDetailedPlayers() {
			$players = array();
			
			$this->sendPacket('d');
			
			fread($this->socket, 11);
			$playerCount = ord(fread($this->socket, 2));
			if($playerCount > 0)
			{
				for($i = 0; $i < $playerCount; $i++)
				{
					$players[] = array (
						'playerid' => ord(fread($this->socket, 1)),
						'nickname' => $this->getPacket(1),
						'score' => $this->toInteger(fread($this->socket, 4)),
						'ping' => $this->toInteger(fread($this->socket, 4))
					);
				}
			}
			return $players;
		}
		
		/***
		 *
		 * @ingnore
		 *
		 ***/
		private function sendPacket($queryParam) {
			$packet  = 'SAMP';
			$packet .= chr(strtok($this->serverinfo['ip'], '.'));
			$packet .= chr(strtok('.')).chr(strtok('.')).chr(strtok('.'));
			$packet .= chr($this->serverinfo['port'] & 0xFF);
			$packet .= chr($this->serverinfo['port'] >> 8 & 0xFF);
			$packet .= $queryParam;
		
			return fwrite($this->socket, $packet); 
		}
		
		/***
		 *
		 * @ingnore
		 *
		 ***/
		private function getPacket($bytes) {
			$response = fread($this->socket, $bytes);
			
			$lenght = ord($response);
            if ($lenght > 0)	
				return fread($this->socket, $lenght);
			
			return null;
		}
		
		/***
		 *
		 * @ingnore
		 *
		 ***/
		private function toInteger($data) {
			$integer = null;
			
			$integer += (ord($data[0]));
			
			if(isset($data[1]))
				$integer += (ord($data[1]) << 8);
				
			if(isset($data[2]))
				$integer += (ord($data[2]) << 16);
			
			if(isset($data[3]))
				$integer += (ord($data[3]) << 24);

			if($integer >= 4294967294)
				$integer -= 4294967296;

			return $integer;
		}
	}
	
	
<?php
/*
Eggdrop:Webinterface
- Eggdrop webinterface to give your usern and/or customers control over there bots.

Copyright (C) 2008 Eric 'take' Kurzhals
    
	www.codershell.org

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>.
*/

/* kernel/ssh.class.php */

class ssh
{
	private $sql;
	private $con;
	
	private static $instance;
	
	public static function getInstance()
	{
		if (!self::$instance)
		{
			self::$instance = new ssh();
		}
		return self::$instance;
	}
	
	/* Konstruktor, Instanz der DB Class einholen */
	function __construct()
	{
		$this->sql = sql::getInstance(); 
	}
	
	/* Verbindung über SSH aufnehmen */
	public function connect ($rootid)
	{
		global $cfg;
		
		$q = $this->sql->query("SELECT ROOT_SSH_IP, ROOT_SSH_USER, ROOT_SSH_PASS, ROOT_SSH_PORT FROM ".prfx."rootserver WHERE ROOT_ID = '".$rootid."'");
		
		$r = $this->sql->content($q);
		
		$blowfish = new Blowfish($cfg["BLOWFISHKEY"]);
		
		$pass = $blowfish->Decrypt($r["ROOT_SSH_PASS"]);
		
		$this->ssh_connect($r["ROOT_SSH_IP"], $r["ROOT_SSH_PORT"], $pass, $r["ROOT_SSH_USER"]);
		
		return true;
		
	}
	
	/* Disconnect */
	public function my_ssh_disconnect($reason, $message, $language) {
		printf("Server disconnected with reason code [%d] and message: %s\n",
		$reason, $message);
		
		return true;
	}
	
	/* Eigentliche ssh_connect Funktion */
	public function ssh_connect($host, $port, $pass, $user="root")
	{
		$methods = array(
		  'kex' => 'diffie-hellman-group1-sha1',
		  'client_to_server' => array(
			'crypt' => '3des-cbc',
			'comp' => 'none'),
		  'server_to_client' => array(
			'crypt' => 'aes256-cbc,aes192-cbc,aes128-cbc',
			'comp' => 'none'));
		
		$callbacks = array();
	
		$this->con = ssh2_connect($host, $port, $methods, $callbacks);
		if (!$this->con) die('Connection failed');
		else {
			
			if (!ssh2_auth_password($this->con, $user, trim($pass))) {
				die("login failed.");
			}
		}
		
		return true;
	}
	
	/* Befehle ausführen */
	public function ssh_exec ($cmd, $stderr=true)
	{
		if ($stderr == true)
		{
			$stream = ssh2_exec($this->con, $cmd);
	
			$err_stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
	
			stream_set_blocking($err_stream, true);
			$result_err = stream_get_contents($err_stream);
			
			if (empty($result_err))
			{
				stream_set_blocking($stream, true);
				
				$out = stream_get_contents($stream);
				
				return $out;
			}
			else {
				return $result_err;
			}
		}
		else {
		
			$stream = ssh2_exec($this->con, $cmd);
			
			stream_set_blocking($stream, true);
			
			$out = stream_get_contents($stream);
			
			return $out;
		}
	}
	
	/* Verbindung beenden */
	public function quit()
	{
		$stream = ssh2_exec($this->con, 'exit');
		stream_set_blocking($stream, true);
		$output = stream_get_contents($stream);

		return true;				
	}
}
?>
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

/* kernel/eggdrop.class.php */

class eggdrop
{
	private $sql;
	private $ssh;
	
	/* Konstruktor, Instanz der DB Class einholen */
	function __construct()
	{
		$this->sql = sql::getInstance(); 
		$this->ssh = ssh::getInstance();
	}
	
	public function refreshConfig ($eggid)
	{
		global $cfg;
		
		$q = $this->sql->query("SELECT * FROM ".prfx."eggdrops AS e LEFT JOIN ".prfx."rootserver AS r ON r.ROOT_ID = e.ROOT_ID WHERE EGG_ID = $eggid");
		$r = $this->sql->content($q);
		
		$lsq = $this->sql->query("SELECT EGG_SCRIPT FROM ".prfx."eggdrops_scripts WHERE EGG_ID = $eggid");
		
		$lscripts = "";
		
		while ($lsr = $this->sql->content($lsq))
		{
			$lscripts .= "\nsource ".$cfg["EGGSCRIPTDIR"].$lsr["EGG_SCRIPT"];
		}
		
		$cfg["EGGCFG"] = preg_replace("/\{\|REALNAME\|\}/si", $r["EGG_CFG_USERNAME"], $cfg["EGGCFG"]);
		$cfg["EGGCFG"] = preg_replace("/\{\|IDENT\|\}/si", $r["EGG_CFG_IDENT"], $cfg["EGGCFG"]);
		$cfg["EGGCFG"] = preg_replace("/\{\|NICKNAME\|\}/si", $r["EGG_CFG_NICKNAME"], $cfg["EGGCFG"]);
		$cfg["EGGCFG"] = preg_replace("/\{\|ALTNICK\|\}/si", $r["EGG_CFG_ALTNICK"], $cfg["EGGCFG"]);
		$cfg["EGGCFG"] = preg_replace("/\{\|ADMIN\|\}/si", $r["EGG_CFG_ADMIN"], $cfg["EGGCFG"]);
		$cfg["EGGCFG"] = preg_replace("/\{\|OWNER\|\}/si", $r["EGG_CFG_ADMIN"], $cfg["EGGCFG"]);
		$cfg["EGGCFG"] = preg_replace("/\{\|CTCP\|\}/si", $r["EGG_CFG_CTCPVERSION"], $cfg["EGGCFG"]);
		$cfg["EGGCFG"] = preg_replace("/\{\|NETWORK\|\}/si", $r["EGG_CFG_NETWORK"], $cfg["EGGCFG"]);
		$cfg["EGGCFG"] = preg_replace("/\{\|HOSTNAME\|\}/si", $r["EGG_CFG_HOSTNAME"], $cfg["EGGCFG"]);
		$cfg["EGGCFG"] = preg_replace("/\{\|IP\|\}/si", $r["EGG_CFG_IP"], $cfg["EGGCFG"]);
		$cfg["EGGCFG"] = preg_replace("/\{\|TELNETPORT\|\}/si", $r["EGG_TELNET_PORT"], $cfg["EGGCFG"]);
		$cfg["EGGCFG"] = preg_replace("/\{\|STDCHAN\|\}/si", $r["EGG_CFG_STDCHAN"], $cfg["EGGCFG"]);
		$cfg["EGGCFG"] = preg_replace("/\{\|IRCSERVER\|\}/si", $r["EGG_CFG_SERVER"], $cfg["EGGCFG"]);
		$cfg["EGGCFG"] = preg_replace("/\{\|IRCPORT\|\}/si", $r["EGG_CFG_NETWORKPORT"], $cfg["EGGCFG"]);
		$cfg["EGGCFG"] = preg_replace("/\{\|LOADSCRIPTS\|\}/si", $lscripts, $cfg["EGGCFG"]);
		
		$file = "temp/".md5(time().$eggid).".tmp";
		$handle = fopen($file, 'w+');
		fwrite($handle, stripslashes($cfg["EGGCFG"]));
		
		// oidentd;
		$oFile = "temp/".md5(time().$eggid."oidentd").".tmp";
		$oHandle = fopen ($oFile, "w+");
		fwrite ($oHandle, 'global { reply "'.$r["EGG_CFG_IDENT"].'"}');
		fclose ($oHandle);
		fclose($handle);
		
		$ftp = new ftp();

		$ftp->connect($r["ROOT_SSH_IP"], $r["ROOT_FTP_PORT"], $r["EGG_FTP_USER"], $r["EGG_FTP_PASS"]);

		$ftp->put($cfg["EGGCFGFILE"], $file);
		$ftp->put (".oidentd.conf", $oFile);
		$ftp->_chmod ("644", ".oidentd.conf");

		$ftp->quit();
		unlink ($file);
		unlink ($oFile);
		return true;
	}
	
	public function install ($eggid)
	{
		
		$q = $this->sql->query("SELECT * FROM ".prfx."eggdrops WHERE EGG_ID = $eggid");
		$r = $this->sql->content($q);
		
		$salt = array(
				"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ".", "\\");
			
		$saltset = $salt[rand(0, 62)].$salt[rand(0, 62)];
		
		echo $this->ssh->ssh_exec ('cd /home/eggdrops/script/ && ./eggdrop.sh install '.$eggid.' '.crypt($r["EGG_FTP_PASS"], $saltset).' '.crypt($r["EGG_SC_FTPP"], $saltset));
		
		echo "installed..";
		return true;
	}
	
	public function createImage ($rootid)
	{
		
		$this->ssh->connect($rootid);
		$this->ssh->ssh_exec ('cd /home/eggdrops/script/ && ./eggdrop.sh create');
		
		return true;
	
	}
	public function del ($eggid)
	{
	
		$this->ssh->ssh_exec('cd /home/eggdrops/script/ && ./eggdrop.sh delete '.$eggid);
	
	}
}
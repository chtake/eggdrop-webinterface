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

/* kernel/users.class.php */

class users
{
	private $sql;
	
	/* Konstruktor, Instanz der DB Class einholen */
	function __construct()
	{
		$this->sql = sql::getInstance(); 
	}

	/* Login */
	public function login ($usernick, $passwd, $md5=false)
	{
	
		if ($md5==false)
		{
			$passwd = md5($passwd);
		}
		
		$qry = $this->sql->query("SELECT USER_ID, USER_PASSWD FROM ".prfx."users WHERE USER_ID = '".$usernick."' AND USER_PASSWD = '".$passwd."'");

		$n = $this->sql->nums($qry);
	
		if ($n != 1)
		{
			
			$lockt = time()+1800;
			$this->sql->query("INSERT INTO ".prfx."loginsecure VALUES ('".$_SERVER['REMOTE_ADDR']."', '".$usernick."', '".$lockt."')");
		#	$this->log->insert($_SERVER['REMOTE_ADDR'], "login", "Fehllogin - Nickname: ".$usernick.", IP-Adresse: ".$_SERVER['REMOTE_ADDR']);
			return false;
			die();	
		}
		else {
			
			$r = $this->sql->content($qry);
			
			$sess = md5($r["USER_ID"].time().$_SERVER['REMOTE_ADDR'].$passwd);
			
			$_SESSION['UID'] = $r["USER_ID"];
			$_SESSION['UPASS'] = $r["USER_PASSWD"];
			$_SESSION['USESS'] = $sess;
			
			#$this->log->insert($_SERVER['REMOTE_ADDR'], "login", "$usernick loggte sich erfolgreich ein.");
			
			$this->sql->query("DELETE FROM ".prfx."users_session WHERE USER_ID ='".$r["USER_ID"]."'");
			$this->sql->query("INSERT INTO ".prfx."users_session VALUES ('".$r["USER_ID"]."', '".$sess."', '".time()."')");
	
			return true;				
		}
	}
	
	/* Zaehler für BruteForce attacken. */
	public function loginfailed ($usernick)
	{
		
		$lockt = time()+1800;
		$this->sql->query("INSERT INTO ".prfx."loginsecure VALUES ('".$_SERVER['REMOTE_ADDR']."', '".$usernick."', '".$lockt."')");
		
		return "Das Passwort stimmt nicht mit deiner eggdrop:webinterface ID überein.";
	}
	
	/* Vorbeugung von BruteForce Attacken */
	public function CheckBruteForce ($usernick, $ip)
	{
		
		$n=time()+3600;
		$this->sql->query("DELETE FROM ".prfx."loginsecure WHERE LS_TIME <= '".$n."'");
		
		$qry = $this->sql->query("SELECT LS_IP FROM ".prfx."loginsecure WHERE LS_IP = '".$_SERVER['REMOTE_ADDR']."'");
		$n = $this->sql->nums($qry);
			
		if ($n > 2) die("Dein eggdrop:webinterface Account ist wegen Loginflooding für 30 Minuten gesperrt. Dies dient zum Schutz vor sog. BruteForce Attacken.");
		
		$qry = $this->sql->query("SELECT USER_ID FROM ".prfx."loginsecure WHERE USER_ID = '".$usernick."'");
		$n = $this->sql->nums($qry);
		if ($n > 2) die("Dein eggdrop:webinterface Account ist wegen Loginflooding für 30 Minuten gesperrt.");
	
	}
	
	/* Ist der User angemeldet am System? */
	public function isLoggedOn ()
	{
	
		$qs = $this->sql->query("SELECT USER_ID FROM ".prfx."users_session WHERE USER_ID = '".$_SESSION['UID']."' AND SESSION_ID = '".$_SESSION['USESS']."'");
		$n = $this->sql->nums($qs);
		
		if ($n == 1)
		{
			$qry = $this->sql->query("SELECT USER_ID, USER_STATUS, USER_NICKNAME
							FROM ".prfx."users
							WHERE
							USER_ID = '".$_SESSION['UID']."'
							AND
							USER_PASSWD = '".$_SESSION['UPASS']."'
							AND
							USER_STATUS > '0'
							");
							
			$n = $this->sql->nums($qry);
			if ($n == 1)
			{
	
				$r = $this->sql->content($qry);
				
				define("USER_ID", $r["USER_ID"]);
				define("USER_STATUS", $r["USER_STATUS"]);
				define("USER_NICK", $this->sql->html($r["USER_NICKNAME"]));

				$sess = md5($r["USER_ID"].time().$_SERVER['REMOTE_ADDR'].$_SESSION['pass']);
			
				$this->sql->query("UPDATE ".prfx."users_session SET SESSION_ID = '".$sess."' WHERE USER_ID = ".USER_ID);
			
				$_SESSION['USESS'] = $sess;
			
				/* User online */
				$this->sql->query("DELETE FROM ".prfx."user_online WHERE USER_ID = ".USER_ID." OR USER_SDEL < '".time()."'");
				$this->sql->query("INSERT INTO ".prfx."user_online VALUES ( '".USER_ID."', '".(time()+300)."')");
				
				return true;
			}
			else {
				
				header("location: login.php");
				die();
				
				return false;
			}
		}
	}
	
	/* Passwort generieren */
	public function makePass ($length=5)
	{
		$salt = array(
				"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ".", "\\");
		
		$passwort = "";
		for ($i=1;$i<=$length;$i++)
		{
			$passwort .= $salt[rand(0, 62)];
		}
		
		return $passwort;
	}

	/* User anlegen */
	public function adduser ($nname="", $vname="", $nickname, $strasse="", $plz="", $ort="", $pass, $susp=0, $status=1)
	{
	
		$q = $this->sql->query("INSERT INTO ".prfx."users VALUES (
											   '',
											   '".$nname."',
											   '".$nickname."',
											   '".$vname."',
											   '".$strasse."',
											   '".$plz."',
											   '".$ort."',
											   '0',
											   '0',
											   '".time()."',
											   '',
											   '".md5($pass)."',
											   '".$status."',
											   '".$susp."')");
		
		if ($q) { return true; } else { return false; }
			
	}
		
	/* User löschen */
	public function deluser ($uid)
	{
		return true;
	}
}

?>
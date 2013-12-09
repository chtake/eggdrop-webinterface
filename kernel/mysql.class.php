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

/* kernel/mysql.class.php */

class sql
{
	
	var $host = "localhost"; // MySQL Server
	var $user = "egg"; // MySQL User
	var $db   = "egg"; // MySQL Datenbank
	var $pass = "egg"; // MySQL Passwort
	
	private static $instance;
	
	public static function getInstance()
	{
		if (!self::$instance)
		{
			self::$instance = new sql();
		}
		return self::$instance;
	}
	
	/* Konstruktor */
	function __construct()
	{
		$this->connect();
	}
	
	// MySQL verbinden
	public function connect ()
	{
	
		mysql_connect($this->host, $this->user, $this->pass);
		mysql_select_db($this->db);
		
	}

	// SQL Befehl ausführen
	public function query ($string)
	{
	
		$result = mysql_query($string);
		/* for Error reporting temporär */
		echo mysql_error();
		return $result;
	
	}
	
	// Num Rows ausgeben
	public function nums ($result)
	{
		
		$num = mysql_num_rows($result);
		return $num;
	
	}
	
	// Fetch Array ausgabe
	public function content ($query)
	{
		$return = mysql_fetch_array($query);
		return $return;
	}
	
	public function html ($string)
	{
		return htmlspecialchars($string);
	}
	// Insert last auto_increment ID
	public function getid ()
	{
		return mysql_insert_id();
	}
	
	// Verbindung beenden
	public function quit ()
	{
	
		mysql_close();
	
	}

}
?>
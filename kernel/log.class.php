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

/* kernel/log.class.php */

class logs
{
	private $sql;
	
	/* Konstruktor, Instanz der DB Class einholen */
	function __construct()
	{
		$this->sql = sql::getInstance(); 
	}

	/* Log einfügen in DB */
	insert ($uid, $area, $msg)
	{
	
		$this->sql->query("INSERT INTO ".prfx."logs VALUES (
						   '',
						   '".time()."',
						   '".$uid."',
						   '".$area."',
						   '".$msg."')");
		return true;
	}
}
?>
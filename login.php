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

/* login.php */
require_once("kernel/config.php");
require_once("kernel/mysql.class.php");
require_once("kernel/users.class.php");
require_once("kernel/blowfish.class.php");
require_once("kernel/eggdrop.class.php");
require_once("kernel/ssh.class.php");
require_once("kernel/ftp.class.php");

if (isset($_POST['login']) && !empty($_POST['uid']) && !empty($_POST['passwort']))
{
	$sql = new sql();
	$users = new users();
	
	$sql->connect();
	
	/* Bruteforce Sperre */
	$users->CheckBruteForce($_POST['uid'], $_SERVER['REMOTE_ADDR']);
	
	
	if ($users->login($_POST['uid'], $_POST["passwort"]) == true)
	{
		
		$sql->query("UPDATE ".prfx."users SET
		USER_STAT_LOGINS = USER_STAT_LOGINS+1,
		USER_STAT_LLOGIN = '".time()."',
		USER_STAT_LASTIP = '".$_SERVER['REMOTE_ADDR']."'
		WHERE USER_ID = '".$_POST['uid']."'");
		
		$_SESSION['language'] = $_POST['language'];
		header("Location: index.php");
	
	}
	else {
		
		echo $users->loginfailed($_POST['uid']);
	
	}
	
	$sql->quit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=WINDOWS-1252" />
<title>Eggdrop Webinterface - &copy; 2oo7 by www.codershell.org</title>
<link rel="stylesheet" type="text/css" href="include/style.css" />
</head>
<body>
<table width="700" border="0" cellpadding="0" cellspacing="0">
 <tr>
   <td height="69" colspan="3"><font style="font-size: 24px; font-weight: bold;">Eggdrop:Webinterface</font></td>
 </tr><tr>
			<td class="authb content unten"><form name="form" method="post" action="">
			  <div align="center">ID<br>
			    <input type="text" name="uid" style="width:150px">
			    <br>
				Password<br>
					<input type="password" name="passwort" style="width:150px">
					<br />
					Language<br />
					<select name="language" style="width:150px">
					<?php
					$opendir = opendir('language/');
					while ($daten = readdir($opendir)) {
						if($daten !="." && $daten !="..") {
							$daten = explode(".", $daten);
							if ($daten[0] != "index")
							{
								echo " <option value='".$daten[0]."'>".ucfirst($daten[0])."</option>";
							}
						}
					}
					?>
			        </select>
					<br>
					<input type="submit" name="login" value="Einloggen">
					<br>
			  </div>
			</form></td>
</tr>
 <tr>
   <td class="authb content unten">&copy; 2007 by <a href="http://www.codershell.org">www.codershell.org&nbsp;</a></td>
 </tr>
</table>
</body>
</html>
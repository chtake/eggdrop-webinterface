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

/* modules/veggdrops.php */

if (!defined("eggif")) header("Location: 404.html");
if (USER_STATUS != 2) header("Location: 404.html");

$sub = $_GET['sub'];

if ($sub == "new")
{
	if (isset($_POST['addegg']))
	{
		if (!empty($_POST['user']) && !empty($_POST['server']) && !empty($_POST['ircn']))
		{
			
			/*
			* Server Verfügbarkeit überprüfen
			*/
			
			$q = $sql->query("SELECT ROOT_STAT_EGGS, ROOT_STAT_MAXEGGS FROM ".prfx."rootserver WHERE ROOT_ID = '".$_POST['server']."' AND ROOT_STAT_EGGS < ROOT_STAT_MAXEGGS");
			$i = $sql->nums($q);
			
			if ($i != 1)
			{
				echo "Dieser Server ist voll, verwenden Sie bitte einen anderen.";
			}
			else {
			
				/*
				* IP organisieren
				*/
				
					$q = $sql->query("SELECT RI_IP, RI_HOST FROM ".prfx."rootserver_ips WHERE ROOT_ID = '".$_POST['server']."' AND RI_STAT_EGGS < RI_STAT_MAXEGGS LIMIT 1");
					$r = $sql->content($q);
					
					$ip = $r["RI_IP"];
					$host = $r["RI_HOST"];
					
					$sql->query("UPDATE ".prfx."rootserver_ips SET RI_STAT_EGGS = RI_STAT_EGGS+1 WHERE RI_IP = '".$ip."'");
			
				/*
				* Network daten
				*/
				
					$q = $sql->query("SELECT * FROM ".prfx."ircnetworks WHERE NETWORK_ID = '".$_POST['ircn']."'");
					$r = $sql->content($q);
				
				/*
				* Allgemeines Eingetrage
				*/
				
					$sql->query("INSERT INTO ".prfx."eggdrops VALUES (
								 '',
								 '".$_POST['user']."',
								 '".$_POST['server']."',
								 '".$ip."',
								 '',
								 '',
								 '',
								 '',
								 '',
								 '',
								 '',
								 '0',
								 '".$_POST['vhosts']."',
								 '".$_POST['lident']."',
								 '".$_POST['nickname']."',
								 '".$_POST['username']."',
								 '".$_POST['ident']."',
								 '".$_POST['altnick']."',
								 '".$_POST['admin']."',
								 '".$_POST['ctcpversion']."',
								 '".$r["NETWORK_NAME"]."',
								 '".$r["NETWORK_PORT"]."',
								 '".$r["NETWORK_SERVER"]."',
								 '".$host."',
								 '".$ip."',
								 '".$_POST['stdchan']."')");
	
				/*
				* restlichen Daten eintragen, nach erhalt der EGG_ID
				*/
				
					$egg_id = $sql->getid();
					
					/* FTP User/Pass */
						$ftpuser = "egg".$egg_id;
						$ftppass = $users->makePass(6);
						
					/* FTP Scripts User/Pass */
						$ftpusers = "eggs".$egg_id;
						$ftppasss = $users->makePass();
					
					/* Telnet */
						$telnetu = "egg".$egg_id;
						$telnetp = $users->makePass();
						
						if ($egg_id < 100) { $telnetport = '400'.$egg_id; }
						elseif ($egg_id < 1000) { $telnetport = '40'.$egg_id; }
						elseif ($egg_id < 10000) { $telnetport = '4'.$egg_id; }
						else { $telnetport = $egg_id; }
					
					/* Neue Werte in DB. eintragen */
					
						$sql->query("UPDATE ".prfx."eggdrops SET
									EGG_FTP_USER = '".$ftpuser."',
									EGG_FTP_PASS = '".$ftppass."',
									EGG_SC_FTPU = '".$ftpusers."',
									EGG_SC_FTPP = '".$ftppasss."',
									EGG_TELNET_PORT = '".$telnetport."',
									EGG_TELNET_PASS = '".$telnetp."',
									EGG_TELNET_USER = '".$telnetu."'
									WHERE EGG_ID = $egg_id");
					
					/* Root-Statistik hinzufügen */
					
						$sql->query("UPDATE ".prfx."rootserver SET ROOT_STAT_EGGS = ROOT_STAT_EGGS+1 WHERE ROOT_ID = '".$_POST['server']."'");
						$sql->query("UPDATE ".prfx."users SET USER_STAT_EGGS = USER_STAT_EGGS+1 WHERE USER_ID = '".$_POST['user']."'");
						
					/* Eggdrop Installieren */
						
						$ssh->connect($_POST['server']);
						
						$eggdrop->install($egg_id);
						$eggdrop->refreshConfig($egg_id);
						
						$ssh->quit();
					
					header("Location: ?go=veggdrops&sub=edit&id=$egg_id");
			}
		}
		else { echo "<b>Sie muessen alle mit * gekennzeichneten Felder ausfuellen.</b>"; }
	
	}
?>
<form name="form1" method="post" action="">
  <table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="4" class="thead"><?=$lang["adm"]["ve"]["add"];?></td>
    </tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>

<tr>
    <td width="25%" class="tleft"><strong><?=$lang["adm"]["ve"]["allg"];?></strong></td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent"><strong><?=$lang["adm"]["ve"]["config"];?></strong></td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>
<tr>
    <td width="25%" class="tleft"><?=$lang["adm"]["ve"]["usr"];?></td>
    <td width="25%" class="tcontent">      <select name="user" style="width:160px;">
    <?
		$q = $sql->query("SELECT USER_NNAME, USER_VNAME, USER_ID FROM ".prfx."users");
		while ($r = $sql->content($q))
		{
	      echo '<option value="'.$r["USER_ID"].'">'.$sql->html($r["USER_NNAME"]).', '.$sql->html($r["USER_VNAME"]).'</option>';
		  echo "\n";
		}
	?>
    </select>
      *</td>
    <td width="25%" class="tcontent"><?=$lang["adm"]["ve"]["usrname"];?></td>
    <td width="25%" class="tright">
      <input type="text" name="username" id="username" value="CodersHell.org Eggdrop:Webinterface">
    </td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["ve"]["srv"];?></td>
  <td class="tcontent"><select name="server" style="width:160px;">
    <?php
		$q = mysql_query("SELECT * FROM ".prfx."rootserver ORDER BY ROOT_NAME ASC");
		while ($r = mysql_fetch_array($q))
		{
			echo '<option value="'.$r["ROOT_ID"].'">'.$sql->html($r["ROOT_NAME"]).'</option>';
			echo "\n";
		}
	?>
  </select>
   *</td>
  <td class="tcontent"><?=$lang["adm"]["ve"]["usrnick"];?></td>
    <td class="tright">
      <input type="text" name="nickname" id="nickname" value="tag_ew">
    </td>
</tr>
<tr>
    <td width="25%" class="tleft"><?=$lang["adm"]["ve"]["vhosts"];?></td>
    <td width="25%" class="tcontent"><select name="vhosts" id="vhosts" style="width:160px;">
      <option value="2"><?=$lang["adm"]["ve"]["vh2"];?></option>
      <option value="1"><?=$lang["adm"]["ve"]["vh1"];?></option>
      <option value="0" selected="selected"><?=$lang["adm"]["ve"]["vh0"];?></option>
    </select>
    </td>
    <td width="25%" valign="top" class="tcontent"><?=$lang["adm"]["ve"]["ident"];?></td>
    <td width="25%" class="tright">
      <input type="text" name="ident" id="ident" value="tag_ew">
    </td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["ve"]["network"];?></td>
  <td class="tcontent"><select name="ircn" style="width:160px;">
    <?php
		$q = mysql_query("SELECT * FROM ".prfx."ircnetworks ORDER BY NETWORK_ID ASC");
		while ($r = mysql_fetch_array($q))
		{
			echo '<option value="'.$r["NETWORK_ID"].'">'.$sql->html($r["NETWORK_NAME"]).'</option>';
			echo "\n";
		}
	?>
  </select>
    *</td>
  <td class="tcontent"><?=$lang["adm"]["ve"]["altnick"];?></td>
  <td class="tright">
    <input type="text" name="altnick" id="altnick" value="tag_ew">
 </td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["ve"]["lident"];?></td>
  <td class="tcontent"><input name="lident" type="checkbox" id="lident" checked></td>
  <td class="tcontent"><?=$lang["adm"]["ve"]["admname"];?></td>
  <td class="tright">
    <input type="text" name="admin" id="admin" value="tag_ew">
 </td>
</tr>
<tr>
  <td class="tleft">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent"><?=$lang["adm"]["ve"]["ctcp"];?></td>
  <td class="tright">
    <input type="text" name="ctcp" id="ctcp" value="">
  </td>
</tr>
<tr>
  <td class="tleft">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent"><?=$lang["adm"]["ve"]["stdchan"];?></td>
  <td class="tright">
    <input type="text" name="stdchan" id="stdchan" value="#CodersHell">
  </td>
</tr>
<tr>
  <td class="tleft">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent">*<strong> <?=$lang["adm"]["ve"]["plfields"];?></strong></td>
    <td width="25%" class="tright"><input type="submit" name="addegg" id="addegg" value="<?=$lang["adm"]["button"]["add"];?>"></td>
</tr>
    <tr>
      <td colspan="4" class="tfoot">&nbsp;</td>
    </tr>
  </table>
</form>
<?
}
elseif ($sub == "edit" && is_numeric($_GET['id']))
{
	$q = $sql->query("SELECT * FROM ".prfx."eggdrops WHERE EGG_ID = ".$_GET['id']);
	$n = $sql->nums($q);
	
	if ($n != 1) { die(); }
	
	$r = $sql->content($q);
	
	if (isset($_POST['editegg']))
	{
	
		
		if (isset($_POST['DELDROP']))
		{
			
			$egg_id = $_GET['id'];
			
			$server = $r["ROOT_ID"];
			
			$ssh->connect($server);
			$eggdrop->del($egg_id);
			$ssh->quit();
			
			$sql->query("DELETE FROM ".prfx."eggdrops WHERE EGG_ID = $egg_id");
			
			$sql->query("UPDATE ".prfx."rootserver_ips SET RI_STAT_EGGS = RI_STAT_EGGS-1 WHERE RI_IP = '".$r["RI_IP"]."'");
			$sql->query("UPDATE ".prfx."rootserver SET ROOT_STAT_EGGS = ROOT_STAT_EGGS-1 WHERE ROOT_ID = '".$server."'");
			$sql->query("UPDATE ".prfx."users SET USER_STAT_EGGS = USER_STAT_EGGS-1 WHERE USER_ID = '".$r["USER_ID"]."'");
			
			header("Location: ?go=veggdrops");
		
		}
		
		/*
		* Network daten
		*/
		
			$q2 = $sql->query("SELECT * FROM ".prfx."ircnetworks WHERE NETWORK_ID = '".$_POST['ircn']."'");
			$r2 = $sql->content($q2);
		
		/*
		* Qry. Update
		*/
		$sql->query("UPDATE ".prfx."eggdrops SET
					 USER_ID = '".$_POST['user']."',
					 EGG_SUSPENDED = '".$_POST['suspend']."',
					 EGG_PRIVVHOSTS = '".$_POST['vhosts']."',
					 EGG_CFG_LOCKIDENT = '".$_POST['lident']."',
					 EGG_CFG_NICKNAME = '".$_POST['nickname']."',
					 EGG_CFG_USERNAME = '".$_POST['username']."',
					 EGG_CFG_IDENT = '".$_POST['ident']."',
					 EGG_CFG_ALTNICK = '".$_POST['altnick']."',
					 EGG_CFG_ADMIN = '".$_POST['admin']."',
					 EGG_CFG_CTCPVERSION = '".$_POST['ctcpversion']."',
					 EGG_CFG_NETWORK = '".$r2["NETWORK_NAME"]."',
					 EGG_CFG_NETWORKPORT = '".$r2["NETWORK_PORT"]."',
					 EGG_CFG_SERVER = '".$r2["NETWORK_SERVER"]."',
					 EGG_CFG_LOCKIDENT  = '".$_POST['lident']."',
					 EGG_CFG_STDCHAN = '".$_POST['stdchan']."'
					 WHERE EGG_ID = '".$_GET['id']."'
					 ");
		
		header("Location: ?go=veggdrops&sub=edit&id=".$_GET['id']);
	}
?>
<form name="form1" method="post" action="">
  <table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="4" class="thead"><?=$lang["adm"]["ve"]["eedit"];?></td>
    </tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>

<tr>
    <td width="25%" class="tleft"><strong><?=$lang["adm"]["ve"]["allg"];?></strong></td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent"><strong><?=$lang["adm"]["ve"]["config"];?></strong></td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>
<tr>
    <td width="25%" class="tleft"><?=$lang["adm"]["ve"]["usr"];?></td>
    <td width="25%" class="tcontent"><select name="user" style="width:160px;">
    <?
		$q2 = $sql->query("SELECT USER_NNAME, USER_VNAME, USER_ID FROM ".prfx."users");
		while ($r2 = $sql->content($q2))
		{
	      echo '<option value="'.$r2["USER_ID"].'"';
		  echo ($r["USER_ID"] == $r2["USER_ID"]) ? " selected" : "";
		  echo '>'.$sql->html($r2["USER_NNAME"]).', '.$sql->html($r2["USER_VNAME"]).'</option>';
		  echo "\n";
		}
	?>
    </select>
      *</td>
    <td width="25%" class="tcontent"><?=$lang["adm"]["ve"]["usrname"];?></td>
    <td width="25%" class="tright">
      <input type="text" name="username" id="username" value="<?=$sql->html($r["EGG_CFG_USERNAME"]);?>">
    </td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["ve"]["srv"];?></td>
  <td class="tcontent"><select name="server" style="width:160px;"  disabled="disabled">
    <?php
		$q2 = mysql_query("SELECT * FROM ".prfx."rootserver ORDER BY ROOT_NAME ASC");
		while ($r2 = mysql_fetch_array($q2))
		{
			echo '<option value="'.$r2["ROOT_ID"].'"';
			echo ($r2["ROOT_ID"] == $r["ROOT_ID"]) ? " selected" : "";
			echo '>'.$sql->html($r2["ROOT_NAME"]).'</option>';
			echo "\n";
		}
	?>
  </select>
   *</td>
  <td class="tcontent"><?=$lang["adm"]["ve"]["usrnick"];?></td>
    <td class="tright">
      <input type="text" name="nickname" id="nickname" value="<?=$sql->html($r["EGG_CFG_NICKNAME"]);?>">
    </td>
</tr>
<tr>
    <td width="25%" class="tleft"><?=$lang["adm"]["ve"]["vhosts"];?></td>
    <td width="25%" class="tcontent"><select name="vhosts" id="vhosts" style="width:160px;">
      <option value="2" <?=($r["EGG_PRIVVHOSTS"] == 2) ? " selected" : "";?>><?=$lang["adm"]["ve"]["vh2"];?></option>
      <option value="1" <?=($r["EGG_PRIVVHOSTS"] == 1) ? " selected" : "";?>><?=$lang["adm"]["ve"]["vh1"];?></option>
      <option value="0" <?=($r["EGG_PRIVVHOSTS"] == 0) ? " selected" : "";?>><?=$lang["adm"]["ve"]["vh0"];?></option>
    </select>
    </td>
    <td width="25%" valign="top" class="tcontent"><?=$lang["adm"]["ve"]["ident"];?></td>
    <td width="25%" class="tright">
      <input type="text" name="ident" id="ident" value="<?=$sql->html($r["EGG_CFG_IDENT"]);?>">
    </td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["ve"]["network"];?></td>
  <td class="tcontent"><select name="ircn" style="width:160px;">
    <?php
		$q2 = mysql_query("SELECT * FROM ".prfx."ircnetworks ORDER BY NETWORK_ID ASC");
		while ($r2 = mysql_fetch_array($q2))
		{
			echo '<option value="'.$r2["NETWORK_ID"].'"';
			echo ($r2["NETWORK_NAME"] == $r["EGG_CFG_NETWORK"]) ? " selected" : "";
			echo '>'.$sql->html($r2["NETWORK_NAME"]).'</option>';
			echo "\n";
		}
	?>
  </select>
    *</td>
  <td class="tcontent"><?=$lang["adm"]["ve"]["altnick"];?></td>
  <td class="tright">
    <input type="text" name="altnick" id="altnick" value="<?=$sql->html($r["EGG_CFG_ALTNICK"]);?>">
 </td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["ve"]["lident"];?></td>
  <td class="tcontent"><input name="lident" type="checkbox" id="lident" <?=($r["EGG_CFG_LOCKIDENT"] == 1) ? " checked" : "";?> value="1"></td>
  <td class="tcontent"><?=$lang["adm"]["ve"]["admname"];?></td>
  <td class="tright">
    <input type="text" name="admin" id="admin" value="<?=$sql->html($r["EGG_CFG_ADMIN"]);?>">
 </td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["ve"]["suspended"];?></td>
  <td class="tcontent"><input name="suspend" type="checkbox" id="suspend" value="1" /></td>
  <td class="tcontent"><?=$lang["adm"]["ve"]["ctcp"];?></td>
  <td class="tright">
    <input type="text" name="ctcp" id="ctcp" value="<?=$sql->html($r["EGG_CFG_CTCPVERSION"]);?>">
  </td>
</tr>
<tr>
  <td class="tleft"><label for="DELDROP">Eggdrop löschen</label></td>
  <td class="tcontent"><input name="DELDROP" type="checkbox" id="DELDROP" value="1" /></td>
  <td class="tcontent"><?=$lang["adm"]["ve"]["stdchan"];?></td>
  <td class="tright">
    <input type="text" name="stdchan" id="stdchan" value="<?=$sql->html($r["EGG_CFG_STDCHAN"]);?>">
  </td>
</tr>
<tr>
  <td class="tleft">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent"><strong>»</strong> <a href="?go=usr&amp;sub=egg&amp;id=<?=$_GET['id'];?>"><?=$lang["adm"]["ve"]["eui"];?></a> <strong>«</strong></td>
    <td width="25%" class="tcontent">*<strong> <?=$lang["adm"]["ve"]["plfields"];?></strong></td>
    <td width="25%" class="tright"><input type="submit" name="editegg" value="<?=$lang["adm"]["button"]["edit"];?>"></td>
</tr>
    <tr>
      <td colspan="4" class="tfoot">&nbsp;</td>
    </tr>
  </table>
</form>
<?php
	if ($r["EGG_PRIVVHOSTS"] == 2)
	{
		
	
		$q = $sql->query("SELECT RI_IP FROM ".prfx."eggdrops_hosts WHERE EGG_ID = '".$_GET['id']."'");
		$pool = array();
		while ($r = $sql->content($q))
		{
			$pool[$r["RI_IP"]] = true;
		}
		
		if (isset($_POST['chngip']))
		{
		
			 /* Alte IPs löschen */ 
			 
			 $sql->query("DELETE FROM ".prfx."eggdrops_hosts WHERE EGG_ID = '".$_GET['id']."'");
			 
			 $pubip = $_POST['pubip'];
			 /* Neue Eintragen */
			 if (is_array($pubip))
			 {
			 
			 	foreach ($pubip as $ip)
				{
					$sql->query("INSERT INTO ".prfx."eggdrops_hosts VALUES (
					             '".$_GET['id']."',
								 '".$ip."')");
				}
				
			 }
			 
			 $privip = $_POST['privip'];
			 if (is_array($privip))
			 {
			 
			 	foreach ($privip as $ip)
				{
					$sql->query("INSERT INTO ".prfx."eggdrops_hosts VALUES (
					             '".$_GET['id']."',
								 '".$ip."')");
					
				}
				
			 }
			 
			 header("Location: ?go=veggdrops&sub=edit&id=".$_GET['id']);
		}
?>
<form name="form1" method="post" action="">
<br />
<table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="4" class="thead"><?=$lang["adm"]["ve"]["vh_verw"];?></td>
    </tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" class="tleft"><strong><?=$lang["adm"]["ve"]["vh_opool"];?></strong></td>
  <td class="tcontent"><strong><?=$lang["adm"]["ve"]["vh_ppool"];?></strong></td>
    <td class="tright">&nbsp;</td>
</tr>
<tr>
    <td colspan="2" class="tleft"><select name="pubip[]" size="15" multiple="multiple" style="width: 300px;">
    <?
		$q = $sql->query("SELECT RI_IP, RI_HOST FROM ".prfx."rootserver_ips WHERE RI_PUBLIC = 1");
		while ($r = $sql->content($q))
		{
	      	echo '<option value="'.$sql->html($r["RI_IP"]).'"';
			echo ($pool[$r["RI_IP"]] == true) ? " selected" : "";
			echo '>'.$sql->html($r["RI_HOST"]).'</option>';
		}
	?>
    </select>    </td>
    <td colspan="2" valign="top" class="tright">
      <select name="privip[]" size="15" multiple="multiple" style="width: 300px;">
    <?
		$q = $sql->query("SELECT RI_IP, RI_HOST FROM ".prfx."rootserver_ips WHERE RI_PUBLIC = 0");
		while ($r = $sql->content($q))
		{
	      	echo '<option value="'.$sql->html($r["RI_IP"]).'"';
			echo ($pool[$r["RI_IP"]] == true) ? " selected" : "";
			echo '>'.$sql->html($r["RI_HOST"]).'</option>';
		}
	?>
      </select>    </td>
    </tr>
<tr>
  <td colspan="2" class="tleft">&nbsp;</td>
  <td colspan="2" valign="top" class="tright">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" class="tleft">&nbsp;</td>
  <td colspan="2" valign="top" class="tright"><input type="submit" name="chngip" id="chngip" value="<?=$lang["adm"]["button"]["aufschalt"];?>" /></td>
</tr>
   <tr>
      <td colspan="4" class="tfoot">&nbsp;</td>
    </tr>
  </table>
</form>
<?php	
	}
}
elseif ($sub == "uebersicht")
{
?>
<table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="34" class="thead">#</td>
    <td width="159" class="thead"><?=$lang["adm"]["ve"]["usr"];?></td>
    <td width="184" class="thead"><?=$lang["adm"]["ve"]["vhost"];?></td>
    <td class="thead"><?=$lang["adm"]["ve"]["network"];?></td>
    <td width="141" class="thead"><?=$lang["adm"]["ve"]["active"];?></td>
    <td width="142" class="thead">&nbsp;</td>
  </tr>
  <?php
  	$q = $sql->query("SELECT EGG_ID, EGG_CFG_IP, EGG_SUSPENDED, EGG_CFG_NETWORK, USER_VNAME, USER_NNAME, e.USER_ID FROM ".prfx."eggdrops AS e
					  LEFT JOIN ".prfx."users AS u ON u.USER_ID = e.USER_ID
					  ORDER BY EGG_ID ASC");
  	$i=1;
	while ($r = $sql->content($q))
	{
	  echo '<tr>
		<td class="tleft"><a href="?go=veggdrops&sub=edit&id='.$r["EGG_ID"].'">#egg'.$i.'</a></td>
		<td class="tcontent"><a href="?go=vuser&sub=showdetails&id='.$r["USER_ID"].'">'.$sql->html($r["USER_NNAME"].", ".$r["USER_VNAME"]).'</a></td>
		<td class="tcontent">'.$sql->html($r["EGG_CFG_IP"]).'</td>
		<td class="tcontent">'.$sql->html($r["EGG_CFG_NETWORK"]).'</td>
		<td class="tcontent">';
		echo ($r["EGG_SUSPENDED"] == 0) ? "<font color=green>".$lang['allg']['yes']."</font>" : "<font color=red>".$lang['allg']['no']."</font>";
		echo '</td>
		<td class="tright"><a href="index.php?go=veggdrops&sub=deldrop&eggid='.$r["EGG_ID"].'"><img src="gfx/delete.png" border="0"></a></td>
	  </tr>';
	  echo "\n";
	  $i++;
  	}
	?>
  <tr>
    <td colspan="7" class="tfoot">&nbsp;</td>
  </tr>
</table>
<?
}
elseif ($sub == "deldrop" && is_numeric ($_GET['eggid']))
{

	$q = $sql->query("SELECT * FROM ".prfx."eggdrops WHERE EGG_ID = ".$_GET['eggid']);
	$n = $sql->nums($q);
	
	if ($n != 1) { die(); }
	
	$r = $sql->content($q);

	$egg_id = $_GET['eggid'];
	
	$server = $r["ROOT_ID"];
	
	$ssh->connect($server);
	$eggdrop->del($egg_id);
	$ssh->quit();
	
	$sql->query("DELETE FROM ".prfx."eggdrops WHERE EGG_ID = $egg_id");
	
	$sql->query("UPDATE ".prfx."rootserver_ips SET RI_STAT_EGGS = RI_STAT_EGGS-1 WHERE RI_IP = '".$r["RI_IP"]."'");
	$sql->query("UPDATE ".prfx."rootserver SET ROOT_STAT_EGGS = ROOT_STAT_EGGS-1 WHERE ROOT_ID = '".$server."'");
	$sql->query("UPDATE ".prfx."users SET USER_STAT_EGGS = USER_STAT_EGGS-1 WHERE USER_ID = '".$r["USER_ID"]."'");
	
	header("Location: ?go=veggdrops&sub=uebersicht");

}
else { header("Location: ?go=veggdrops&sub=uebersicht");
}
?>

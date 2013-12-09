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

/* modules/vserver.php */

if (!defined("eggif")) header("Location: 404.html");
if (USER_STATUS != 2) header("Location: 404.html");

$sub = $_GET['sub'];

/* Neuen Server hinzufügen */
if ($sub == "new")
{
	if (isset($_POST['addsrv']))
	{
		
		if (!empty($_POST['name']) && !empty($_POST['ftpport']) && !empty($_POST['sshuser']) && !empty($_POST['sshpass']) && !empty($_POST['sship']))
		{
			
			/*
			*	Root Passwort verschlüsseln
			*/
			
				$blowfish = new Blowfish($cfg["BLOWFISHKEY"]);
				$cipher = $blowfish->Encrypt($_POST['sshpass']);
			
			/*
			* weiter gehts..
			*/
			$sql->query("INSERT INTO ".prfx."rootserver VALUES (
						 '',
						 '".$_POST['name']."',
						 '".$_POST['cpu']."',
						 '".$_POST['ram']."',
						 '".$_POST['extras']."',
						 '".$_POST['distri']."',
						 '".$_POST['sshuser']."',
						 '".$cipher."',
						 '".$_POST['sshport']."',
						 '".$_POST['sship']."',
						 '".$_POST['ftpport']."',
						 '0',
						 '0')");
			$id = $sql->getid();

			/* Server konfigurieren, momentan out-comment */			
			$eggdrop->createImage($id);
			
			
			header("Location: ?go=vserver&sub=edit&id=$id");
		}
		else { echo "<br><b>".$lang['adm']['vs']['allfields']."</b><br><br>"; }
	
	}
?>
<form name="form1" method="post" action="">
  <table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="4" class="thead"><?=$lang["adm"]["vs"]["addsrv"];?></td>
    </tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>

<tr>
    <td width="25%" class="tleft"><strong><?=$lang["adm"]["vs"]["allg"];?></strong></td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent"><strong><?=$lang["adm"]["vs"]["hw"];?></strong></td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>
<tr>
    <td width="25%" class="tleft"><?=$lang["adm"]["vs"]["srvname"];?></td>
    <td width="25%" class="tcontent"><input type="text" name="name" id="name" value="">
      *</td>
    <td width="25%" class="tcontent"><?=$lang["adm"]["vs"]["cpu"];?></td>
    <td width="25%" class="tright">
      <input type="text" name="cpu" id="cpu" value="">
    </td>
</tr>
<tr>
  <td class="tleft">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent"><?=$lang["adm"]["vs"]["ram"];?></td>
    <td class="tright">
      <input type="text" name="ram" id="ram" value="">
    </td>
</tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" valign="top" class="tcontent"><?=$lang["adm"]["vs"]["extras"];?></td>
    <td width="25%" class="tright">
      <textarea name="extras" id="extras"></textarea>
    </td>
</tr>
<tr>
  <td class="tleft"><strong><?=$lang["adm"]["vs"]["conns"];?></strong></td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["vs"]["sshu"];?></td>
  <td class="tcontent"><input type="text" name="sshuser" id="sshuser" value="root">
    *</td>
  <td class="tcontent"><?=$lang["adm"]["vs"]["ftpp"];?></td>
  <td class="tright"><span class="tcontent">
    <input type="text" name="ftpport" id="ftpport" value="">
  *</span></td>
</tr>
<tr>
    <td width="25%" class="tleft"><?=$lang["adm"]["vs"]["sshpass"];?></td>
    <td width="25%" class="tcontent"><label>
      <input type="text" name="sshpass" id="sshpass" value="">
    *</label></td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["vs"]["sshp"];?></td>
  <td class="tcontent"><input type="text" name="sshport" id="sshport" value="">
    *</td>
  <td colspan="2" rowspan="3" class="tright"><?=$lang["adm"]["vs"]["infos"];?> <a href="http://www.codershell.org">http://www.codershell.org</a></td>
  </tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["vs"]["sshi"];?></td>
  <td class="tcontent"><input type="text" name="sship" id="sship" value="   .   .   .   .">
    *</td>
  </tr>
<tr>
  <td class="tleft">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  </tr>
<tr>
    <td width="25%" class="tleft"><strong><?=$lang["adm"]["vs"]["distri"];?></strong></td>
    <td width="25%" class="tcontent"><select name="distri" id="distri">
      <option value="deb" selected>Debian 4.0</option>
      <option value="sus">SuSE</option>
      <option value="cos">Cent-OS</option>
    </select>
    *</td>
    <td width="25%" class="tcontent">*<strong> <?=$lang["adm"]["vs"]["pfl"];?></strong></td>
    <td width="25%" class="tright"><input type="submit" name="addsrv" id="addsrv" value="<?=$lang["adm"]["button"]["add"];?>"></td>
</tr>
    <tr>
      <td colspan="4" class="tfoot">&nbsp;</td>
    </tr>
  </table>
</form>

<br>
<br>
<?=$lang["adm"]["vs"]["infos2"];?>
<?php	
}

elseif ($sub == "edit")
{
	$id = $_GET['id'];
	
	if (!is_numeric($id))
	{
		die($lang["adm"]["vs"]["dexists"]);
	}
	else {
		
		$q = $sql->query("SELECT * FROM ".prfx."rootserver WHERE ROOT_ID = $id");
		
		$n = $sql->nums($q);
		
		if ($n != 1) { die($lang["adm"]["vs"]["dexists"]); }
		
		$r = $sql->content($q);
		
		if (isset($_POST['editsrv']))
		{
			if ($_POST['DELSRV'] == 1)
			{
				
					$rid = $r["ROOT_ID"];
					$ssh->connect($rid);
					
					$q = $sql->query("SELECT EGG_ID FROM ".prfx."eggdrops WHERE ROOT_ID = '".$rid."'");
					
					while ($r = $sql->content($q))
					{
					
						echo $eggdrop->del($r["EGG_ID"]);
						$sql->query("DELETE FROM ".prfx."eggdrops_hosts WHERE EGG_ID = '".$r["EGG_ID"]."'");
						$sql->query("DELETE FROM ".prfx."eggdrops_scripts WHERE EGG_ID = '".$r["EGG_ID"]."'");
				
					}
			
					$ssh->quit();
				
					$sql->query("DELETE FROM ".prfx."eggdrops WHERE ROOT_ID = '".$rid."'");
					$sql->query("DELETE FROM ".prfx."rootserver_ips WHERE ROOT_ID = '".$rid."'");
					$sql->query("DELETE FROM ".prfx."rootserver WHERE ROOT_ID = '".$rid."'");
				
			#	die("Server gelöscht");
			}
			
			
			$sql->query("UPDATE ".prfx."rootserver SET
						ROOT_NAME = '".$_POST['name']."',
						ROOT_HW_CPU = '".$_POST['cpu']."',
						ROOT_HW_RAM = '".$_POST['ram']."',
						ROOT_HW_EXTRAS = '".$_POST['extras']."',
						ROOT_SSH_USER = '".$_POST['sshuser']."',
						ROOT_SSH_PORT = '".$_POST['sshport']."',
						ROOT_SSH_IP = '".$_POST['sship']."',
						ROOT_FTP_PORT = '".$_POST['ftpport']."'
						WHERE ROOT_ID = $id
						");
				
				if (!empty($_POST['sshpass']))
				{
					
					$blowfish = new Blowfish($cfg["BLOWFISHKEY"]);
					$cipher = $blowfish->Encrypt($_POST['sshpass']);
					
					$sql->query("UPDATE ".prfx."rootserver SET ROOT_SSH_PASS = '".$cipher."' WHERE ROOT_ID = $id");
				
				}
						
			header("Location: ?go=vserver&sub=edit&id=$id");
		}
		elseif (isset($_POST['addip']) && !empty($_POST['nip']))
		{
		
			if (empty($_POST['nhost']))
			{
				$host = gethostbyaddr($_POST['nip']);
			}
			else { $host = $_POST['nhost']; }
			
			$sql->query("INSERT INTO ".prfx."rootserver_ips VALUES (
						 '$id',
						 '".$_POST['nip']."',
						 '".$host."',
						 '1',
						 '0',
						 '".$_POST['eggs']."')");
			
			$sql->query("UPDATE ".prfx."rootserver SET ROOT_STAT_MAXEGGS = (ROOT_STAT_MAXEGGS+".$_POST['eggs'].") WHERE ROOT_ID = ".$id);
		
		}
	}
?>
<form name="form1" method="post" action="">
  <table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="4" class="thead"><?=$lang["adm"]["vs"]["srvedit"];?></td>
    </tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>

<tr>
    <td width="25%" class="tleft"><strong><?=$lang["adm"]["vs"]["allg"];?></strong></td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent"><strong><?=$lang["adm"]["vs"]["hw"];?></strong></td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>
<tr>
    <td width="25%" class="tleft"><?=$lang["adm"]["vs"]["srvname"];?></td>
    <td width="25%" class="tcontent"><input type="text" name="name" id="name" value="<?=$sql->html($r["ROOT_NAME"]);?>">
      *</td>
    <td width="25%" class="tcontent"><?=$lang["adm"]["vs"]["cpu"];?></td>
    <td width="25%" class="tright"><input type="text" name="cpu" id="cpu" value="<?=$sql->html($r["ROOT_HW_CPU"]);?>"></td>
</tr>
<tr>
  <td class="tleft">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent"><?=$lang["adm"]["vs"]["ram"];?></td>
    <td class="tright"><input type="text" name="ram" id="ram" value="<?=$sql->html($r["ROOT_HW_RAM"]);?>"></td>
</tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" valign="top" class="tcontent"><?=$lang["adm"]["vs"]["extras"];?></td>
    <td width="25%" class="tright"><textarea name="extras" id="extras"><?=$sql->html($r["ROOT_HW_EXTRAS"]);?></textarea></td>
</tr>
<tr>
  <td class="tleft"><strong><?=$lang["adm"]["vs"]["conns"];?></strong></td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["vs"]["sshu"];?></td>
  <td class="tcontent"><input type="text" name="sshuser" id="sshuser" value="<?=$sql->html($r["ROOT_SSH_USER"]);?>"> *</td>
  <td class="tcontent"><?=$lang["adm"]["vs"]["ftpp"];?></td>
  <td class="tright"><input type="text" name="ftpport" id="ftpport" value="<?=$sql->html($r["ROOT_FTP_PORT"]);?>"> *</td>
</tr>
<tr>
    <td width="25%" class="tleft"><?=$lang["adm"]["vs"]["sshpass"];?></td>
    <td width="25%" class="tcontent"><input type="text" name="sshpass" id="sshpass" value=""> *</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["vs"]["sshp"];?></td>
  <td class="tcontent"><input type="text" name="sshport" id="sshport" value="<?=$sql->html($r["ROOT_SSH_PORT"]);?>">
    *</td>
  <td colspan="2" rowspan="3" class="tright">&nbsp;</td>
  </tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["vs"]["sshi"];?></td>
  <td class="tcontent"><input type="text" name="sship" id="sship" value="<?=$sql->html($r["ROOT_SSH_IP"]);?>">
    *</td>
  </tr>
<tr>
  <td class="tleft"><?=$lang["allg"]["del"];?></td>
  <td class="tcontent"><input name="DELSRV" type="checkbox" id="DELSRV" value="1" /></td>
  </tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent">*<strong> <?=$lang["adm"]["vs"]["pfl"];?></strong></td>
    <td width="25%" class="tright"><input type="submit" name="editsrv" id="editsrv" value="editieren"></td>
</tr>
<tr>
  <td class="tleft">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft"><strong><?=$lang["adm"]["vs"]["ipvh"];?>:</strong></td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
  <td valign="top" class="tleft"><?=$lang["adm"]["vs"]["nip"];?>:</td>
  <td class="tcontent"><input type="text" name="nip" id="nip"></td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["vs"]["host"];?>:</td>
  <td class="tcontent"><input type="text" name="nhost" id="nhost"></td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft">Eggdrops:</td>
  <td class="tcontent"><input name="eggs" type="text" id="eggs" value="5"></td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft">&nbsp;</td>
  <td class="tcontent"><input type="submit" name="addip" id="addip" value="<?=$lang["adm"]["vs"]["addip"];?>"></td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
  <td colspan="2" class="tleft"><table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="79%"><strong><?=$lang["adm"]["vs"]["vhost"];?></strong></td>
      <td width="21%"><strong><?=$lang["adm"]["vs"]["pub"];?></strong></td>
      </tr>
    <?php
		$q = $sql->query("SELECT * FROM ".prfx."rootserver_ips WHERE ROOT_ID = $id");
		while ($r = $sql->content($q))
		{
			echo '<tr>
			  <td><a href="?go=vserver&sub=editips&ip='.$r["RI_IP"].'">'.$sql->html($r["RI_HOST"]).'</a></td>
			  <td>';
			  echo ($r["RI_PUBLIC"] == 1) ? "<font color=green>".$lang["allg"]["yes"]."</font>" : "<font color=red>".$lang["allg"]["no"]."</font>";
			  echo '</td>
			</tr>';
		}
	?>
  </table></td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
    <tr>
      <td colspan="4" class="tfoot">&nbsp;</td>
    </tr>
  </table>
</form>
<?php
}
elseif ($sub == "editips")
{
	$ip = $_GET['ip'];
	
	$q = $sql->query("SELECT ROOT_ID, RI_IP, RI_HOST, RI_PUBLIC, RI_STAT_MAXEGGS FROM ".prfx."rootserver_ips WHERE RI_IP = '".$ip."'");
	
	$n = $sql->nums($q);
	
	if ($n != 1) { die($lang["adm"]["vs"]["ipdexists"]); }
	
	$r = $sql->content($q);
	
	if (isset($_POST['editip']))
	{
		if ($_POST['del'] == 1)
		{
			$sql->query("DELETE FROM ".prfx."rootserver_ips WHERE RI_IP = '".$_GET['ip']."'");
		}
		elseif (!empty ($_POST['ip'])) {
		
			$host = (empty ($_GET['host']) ) ? (
						(gethostbyaddr ($_POST['ip']) == "") ? $_POST['ip'] : gethostbyaddr ($_POST['ip'])
					)
					: $_POST['ip'];
			$sql->query("UPDATE ".prfx."rootserver_ips SET
						RI_IP = '".$_POST['ip']."',
						RI_PUBLIC = '".$_POST['public']."',
						RI_HOST = '".$host."',
						RI_STAT_MAXEGGS = '".$_POST['eggs']."'
						WHERE RI_IP = '$ip'");
			
			$sql->query("UPDATE ".prfx."rootserver SET ROOT_STAT_MAXEGGS = (ROOT_STAT_MAXEGGS-".$r["RI_STAT_MAXEGGS"]."+".$_POST['eggs'].") WHERE ROOT_ID = ".$r["ROOT_ID"]);
		}
		if ($ip != $_POST['ip'])
		{
		
		#	$sql->query("UPDATE ".prfx."eggdrops SET RI_IP = '".$_POST['ip']."' WHERE RI_IP = $ip");
		# eggs neu starten?
			
		}
		
		/* Löschen
		*
		* und Ips bei Eggdrops ersetzen.
		*
		 */
		header("Location: ?go=vserver&sub=editips&ip=".$_POST['ip']);
		
	}
	
?>
<form name="form1" method="post" action="">
  <table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="4" class="thead"><?=$lang["adm"]["vs"]["editip"];?></td>
    </tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>

<tr>
    <td width="25%" class="tleft"><?=$lang["adm"]["vs"]["ip"];?></td>
    <td width="25%" class="tcontent"><input type="text" name="ip" id="ip" value="<?=$sql->html($r["RI_IP"]);?>"></td>
    <td width="25%" class="tcontent"><?=$lang["adm"]["vs"]["pub"];?></td>
    <td width="25%" class="tright"><input name="public" type="checkbox" id="public" value="1" <?=($r["RI_PUBLIC"] == 1) ? " checked" : "";?>></td>
</tr>
<tr>
    <td width="25%" class="tleft"><?=$lang["adm"]["vs"]["host"];?></td>
    <td width="25%" class="tcontent"><input type="text" name="host" id="host" value="<?=$sql->html($r["RI_HOST"]);?>"></td>
    <td class="tcontent"><?=$lang["adm"]["vs"]["mconns"];?></td>
    <td width="25%" class="tright"><input type="text" name="eggs" id="eggs"  value="<?=$sql->html($r["RI_STAT_MAXEGGS"]);?>"></td>
</tr>
<tr>
  <td class="tleft">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
    <td class="tright">&nbsp;</td>
</tr>
<tr>
    <td width="25%" class="tleft"><?=$lang["allg"]["del"];?></td>
    <td width="25%" class="tcontent"><input name="del" type="checkbox" id="del" value="1"></td>
    <td width="25%" valign="top" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>

<tr>
  <td class="tleft">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent"><input type="submit" name="editip" id="editip" value="<?=$lang["adm"]["button"]["edit"];?>"></td>
  <td class="tright">&nbsp;</td>
</tr>
    <tr>
      <td colspan="4" class="tfoot">&nbsp;</td>
    </tr>
  </table>
</form>

<?php
}
elseif ($sub == "uebersicht")
{
	$q = $sql->query("SELECT ROOT_ID, ROOT_NAME, ROOT_SSH_IP, ROOT_STAT_EGGS, ROOT_STAT_MAXEGGS FROM ".prfx."rootserver");
?>
<table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="15" class="thead">#</td>
    <td width="52%" class="thead"><?=$lang["adm"]["vs"]["srvname"];?></td>
    <td width="9%" class="thead">IP</td>
    <td width="36%" class="thead">Eggdrops</td>
  </tr>
  <?php
  	$i=1;
	while ($r = $sql->content($q))
	{
	  echo '<tr>
		<td class="tleft"><a href="?go=vserver&sub=edit&id='.$r["ROOT_ID"].'">#'.$i.'</a></td>
		<td class="tcontent"><a href="?go=vserver&sub=edit&id='.$r["ROOT_ID"].'">'.$sql->html($r["ROOT_NAME"]).'</a></td>
		<td class="tcontent">'.$sql->html($r["ROOT_SSH_IP"]).'</td>
		<td class="tright">('.$r["ROOT_STAT_EGGS"].'/'.$r["ROOT_STAT_MAXEGGS"].')</td>
	  </tr>';
	  echo "\n";
	  $i++;
  	}
	?>
  <tr>
    <td colspan="4" class="tfoot">&nbsp;</td>
  </tr>
</table>
<?php
}
elseif ($sub == "stats") {
// Roots;
$qry = $sql->query ("SELECT ROOT_ID FROM ".prfx."rootserver");
$roots = $sql->nums ($qry);
// IPs;
$qry = $sql->query( "SELECT RI_IP FROM ".prfx."rootserver_ips");
$ips = $sql->nums ($qry);
// users;
$qry = $sql->query ("SELECT USER_ID FROM ".prfx."users");
$users = $sql->nums ($qry);
// Eggdrops;
$qry = $sql->query( "SELECT EGG_ID FROM ".prfx."eggdrops");
$eggs = $sql->nums ($qry);
?>
<fieldset><legend>Statistik</legend>

<table width="632" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td width="184">Rootserver</td>
    <td width="444"><?=$roots;?></td>
  </tr>
  <tr>
    <td>IP-Adressen</td>
    <td><?=$ips;?></td>
  </tr>
  <tr>
    <td>Eggdrops</td>
    <td><?=$eggs;?></td>
  </tr>
  <tr>
    <td>User</td>
    <td><?=$users;?></td>
  </tr>
</table>
</fieldset>
<? } ?>
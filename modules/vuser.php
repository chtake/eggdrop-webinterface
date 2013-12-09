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

/* modules/vuser.php */

if (!defined("eggif")) header("Location: 404.html");
if (USER_STATUS != 2) header("Location: 404.html");

$sub = $_GET['sub'];

if ($sub == "new")
{
	if(isset($_POST['addUser']) && !empty($_POST['nickname']))
	{
	
		$users->adduser($_POST['nname'], $_POST['vname'], $_POST['nickname'], $_POST['strasse'], $_POST['plz'], $_POST['ort'], $_POST['pass'], $_POST['susp'], $_POST['usrstatus']);
		
		//$msg->useradd();
	}
?>

<form name="form1" method="post" action="">
  <table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="4" class="thead"><?=$lang["adm"]["us"]["uadd"];?></td>
    </tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>

<tr>
    <td width="25%" class="tleft"><?=$lang["adm"]["us"]["nname"];?></td>
    <td width="25%" class="tcontent"><input type="text" name="nname" id="nname" value=""></td>
    <td width="25%" class="tcontent"><?=$lang["adm"]["us"]["vname"];?></td>
    <td width="25%" class="tright"><input type="text" name="vname" id="vname" value=""></td>
</tr>
<tr>
    <td width="25%" class="tleft"><?=$lang["adm"]["us"]["nickname"];?></td>
    <td width="25%" class="tcontent"><input type="text" name="nickname" id="nickname" value="">*</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["us"]["street"];?></td>
  <td class="tcontent"><input type="text" name="strasse" id="strasse"  value=""></td>
    <td class="tcontent"><?=$lang["adm"]["us"]["plzort"];?></td>
    <td class="tright"><input name="plz" type="text" id="plz" value="" size="4" maxlength="6"> <input name="ort" type="text" id="ort" value="" size="10"></td>
</tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["us"]["pass"];?></td>
  <td class="tcontent"><input type="text" name="pass" id="pass"  value="<?=$users->makePass(5);?>"></td>
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
    <td width="25%" class="tleft"><label for="susp"><?=$lang["adm"]["us"]["susp"];?></label></td>
    <td width="25%" class="tcontent"><label>
      <input name="susp" type="checkbox" id="susp" value="1">
    </label></td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["us"]["members"];?></td>
  <td class="tcontent"><select name="usrstatus">
    <option value="1" selected<?=($r["USER_STATUS"] == 1) ? " selected" : "";?>><?=$lang["adm"]["us"]["nuser"];?></option>
    <option value="2"><?=$lang["adm"]["us"]["auser"];?></option>
  </select>  </td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent">* <?=$lang["adm"]["us"]["pfl"];?></td>
    <td width="25%" class="tright"><input type="submit" name="addUser" id="addUser" value="<?=$lang["adm"]["button"]["add"];?>"></td>
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
?>
<table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" class="thead"><?=$lang["adm"]["us"]["nickname"];?></td>
    <td width="25%" class="thead"><?=$lang["adm"]["us"]["name"];?></td>
    <td width="25%" class="thead"><?=$lang["adm"]["us"]["llogin"];?></td>
    <td width="25%" class="thead">Eggdrops</td>
  </tr>
  <?php
  
  	$q = $sql->query("SELECT * FROM ".prfx."users ORDER BY USER_ID ASC");
	while ($r = $sql->content($q))
	{
	  echo '<tr>
		<td class="tleft"><a href="?go=vuser&sub=showdetails&id='.$sql->html($r["USER_ID"]).'">'.$sql->html($r["USER_NICKNAME"]).'</a></td>
		<td class="tcontent">'.$sql->html($r["USER_VNAME"])." ".$sql->html($r["USER_NNAME"]).'</td>
		<td class="tcontent">'.date("d.m.Y", $sql->html($r["USER_STAT_LLOGIN"])).'</td>
		<td class="tright">'.$sql->html($r["USER_STAT_EGGS"]).'</td>
	  </tr>';
	  echo "\n";
  	}
	?>
    <tr>
    <td colspan="4" class="tfoot">&nbsp;</td>
  </tr>
</table>
<?php
}
elseif ($sub == "showdetails" && is_numeric($_GET['id']))
{
	$id = $_GET['id'];
	$q = $sql->query("SELECT * FROM ".prfx."users WHERE USER_ID = ".$id);
	
	$n = $sql->nums($q);
	
	if ($n != 1) die("Dieser User existiert nicht.");
	
	$r = $sql->content($q);
	
	if (isset($_POST['editUser']) && !empty($_POST['nickname']))
	{

		if ($_POST['deluser'] == 1)
		{
			
			$q = $sql->query("SELECT EGG_ID, ROOT_ID FROM ".prfx."eggdrops WHERE USER_ID = $id");
			$n = $sql->nums($q);
			
			while ($r = $sql->content($q))
			{
			
				$ssh->connect($r["ROOT_ID"]);
				$eggdrop->del($r["EGG_ID"]);
				$ssh->quit();
			
			}
			
			$sql->query("DELETE e.*, h.*, s.* FROM ".prfx."eggdrops AS e 
						 LEFT JOIN ".prfx."eggdrops_hosts AS h ON h.EGG_ID = e.EGG_ID
						 LEFT JOIN ".prfx."eggdrops_scripts AS s ON s.EGG_ID = e.EGG_ID
						 WHERE e.USER_ID = $id");
			$sql->query("DELETE FROM ".prfx."users WHERE USER_ID = $id");
			
			header("Location: ?go=vserver&sub=showdetails&id=$id");
		}
		
		$sql->query("UPDATE ".prfx."users SET
								USER_NNAME = '".$_POST['nname']."',
								USER_NICKNAME = '".$_POST['nickname']."',
								USER_VNAME = '".$_POST['vname']."',
								USER_STRASSE = '".$_POST['strasse']."',
								USER_PLZ = '".$_POST['plz']."',
								USER_ORT = '".$_POST['ort']."',
								USER_STATUS = '".$_POST['usrstatus']."',
								USER_SUSPENDED = '".$_POST['susp']."'
								WHERE USER_ID = ".$id);
		
		if (!empty($_POST['pass']))
		{
			$sql->query("UPDATE ".prfx."users SET
									USER_PASSWD = '".md5($_POST['pass'])."'
									WHERE USER_ID = ".$id);
		}
		
		header("Location: ?go=vuser&sub=showdetails&id=".$id);
		
	}
?>
<form name="form1" method="post" action="">
  <table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="4" class="thead"><?=$lang["adm"]["us"]["uedit"];?></td>
    </tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>

<tr>
    <td width="25%" class="tleft"><?=$lang["adm"]["us"]["nname"];?></td>
    <td width="25%" class="tcontent"><input type="text" name="nname" id="nname" value="<?=$r["USER_NNAME"];?>"></td>
    <td width="25%" class="tcontent"><?=$lang["adm"]["us"]["vname"];?></td>
    <td width="25%" class="tright"><input type="text" name="vname" id="vname" value="<?=$r["USER_VNAME"];?>"></td>
</tr>
<tr>
    <td width="25%" class="tleft"><?=$lang["adm"]["us"]["nickname"];?></td>
    <td width="25%" class="tcontent"><input type="text" name="nickname" id="nickname" value="<?=$r["USER_NICKNAME"];?>">*</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["us"]["street"];?></td>
  <td class="tcontent"><input type="text" name="strasse" id="strasse"  value="<?=$r["USER_STRASSE"];?>"></td>
    <td class="tcontent"><?=$lang["adm"]["us"]["plzort"];?></td>
    <td class="tright"><input name="plz" type="text" id="plz" value="<?=$r["USER_PLZ"];?>" size="4" maxlength="6"> <input name="ort" type="text" id="ort" value="<?=$r["USER_ORT"];?>" size="10"></td>
</tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["us"]["pass"];?></td>
  <td class="tcontent"><input type="text" name="pass" id="pass"></td>
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
    <td width="25%" class="tleft"><label for="susp"><?=$lang["adm"]["us"]["susp"];?></label></td>
    <td width="25%" class="tcontent"><label>
      <input name="susp" type="checkbox" id="susp" value="1" <?=($r["USER_USER_SUSPENDED"] == 1) ? " checked" : "";?>>
    </label></td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft"><?=$lang["allg"]["del"];?></td>
  <td class="tcontent"><input name="deluser" type="checkbox" id="deluser" value="1" /></td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
  <td class="tleft"><?=$lang["adm"]["us"]["members"];?></td>
  <td class="tcontent"><select name="usrstatus">
    <option value="1" <?=($r["USER_STATUS"] == 1) ? " selected" : "";?>><?=$lang["adm"]["us"]["nuser"];?></option>
    <option value="2" <?=($r["USER_STATUS"] == 2) ? " selected" : "";?>><?=$lang["adm"]["us"]["auser"];?></option>
  </select>  </td>
  <td class="tcontent">&nbsp;</td>
  <td class="tright">&nbsp;</td>
</tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tcontent">&nbsp;</td>
    <td width="25%" class="tcontent">* <?=$lang["adm"]["us"]["pass"];?></td>
    <td width="25%" class="tright"><input type="submit" name="editUser" id="editUser" value="<?=$lang["adm"]["button"]["edit"];?>"></td>
</tr>
    <tr>
      <td colspan="4" class="tfoot">&nbsp;</td>
    </tr>
  </table>
</form>
<?php
}
elseif ($sub == "suche")
{
?>
<form name="form1" method="post" action="">
  <table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2" class="thead"><?=$lang["adm"]["us"]["usearch"];?></td>
    </tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
    </tr>

<tr>
    <td width="25%" class="tleft">ID</td>
    <td width="25%" class="tright"><input type="text" name="id" id="id" value="<?=$_POST['id'];?>"></td>
    </tr>
<tr>
    <td width="25%" class="tleft"> <?=$lang["adm"]["us"]["nickname"];?></td>
    <td width="25%" class="tright"><input type="text" name="nickname" id="nickname" value="<?=$_POST['nickname'];?>"></td>
    </tr>
<tr>
  <td class="tleft"> Eggdrops</td>
  <td class="tright"><input type="text" name="eggs" id="eggs" value="<?=(is_numeric($_POST['eggs'])) ? $_POST['eggs'] : 0;?>"></td>
  </tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tright">&nbsp;</td>
    </tr>
<tr>
    <td width="25%" class="tleft">&nbsp;</td>
    <td width="25%" class="tright"><input type="submit" name="goSearch" id="goSearch" value="Suche starten"></td>
    </tr>
    <tr>
      <td colspan="2" class="tfoot">&nbsp;</td>
    </tr>
  </table>
</form>
<?php
	if (isset($_POST['goSearch']))
	{
		$id = $_POST['id'];
		$nick = $_POST['nickname'];
		$eggs = $_POST['eggs'];
		
		$eggs = (is_numeric($eggs)) ? $eggs : 0;
		
		$q = $sql->query("SELECT * FROM ".prfx."users WHERE USER_ID LIKE '%".$id."%' AND USER_NICKNAME LIKE '%".$nick."%' AND ".$eggs." <= USER_STAT_EGGS");
		if ($sql->nums($q) > 0)
		{
		
?>
<br>
<br>
<table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td class="thead"><?=$lang["adm"]["us"]["nickname"];?></td>
    <td class="thead"><?=$lang["adm"]["us"]["name"];?></td>
    <td class="thead"><?=$lang["adm"]["us"]["llogin"];?></td>
    <td class="thead">Eggdrops</td>
  </tr>
  <?php
	while ($r = $sql->content($q))
	{
	  echo '<tr>
		<td class="tleft"><a href="?go=vuser&sub=showdetails&id='.$sql->html($r["USER_ID"]).'">'.$sql->html($r["USER_NICKNAME"]).'</a></td>
		<td class="tcontent">'.$sql->html($r["USER_VNAME"])." ".$sql->html($r["USER_NNAME"]).'</td>
		<td class="tcontent">'.date("d.m.Y", $sql->html($r["USER_STAT_LLOGIN"])).'</td>
		<td class="tright">'.$sql->html($r["USER_STAT_EGGS"]).'</td>
	  </tr>';
	  echo "\n";
  	}
	?>
  <tr>
    <td width="100%" colspan="4" class="tfoot">&nbsp;</td>
  </tr>
</table>
<?php
		}
		
		else { echo "<h2>".$lang["adm"]["us"]["sa"]."</h2>"; }
	
	}
}
?>

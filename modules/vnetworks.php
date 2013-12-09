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

/* modules/vnetworks.php */

if (!defined("eggif")) header("Location: 404.html");
if (USER_STATUS != 2) header("Location: 404.html");

?>
<form name="form1" method="post" action="">
  <table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2" class="thead"><?=$lang["adm"]["nw"]["ircnws"];?> [<span onClick="window.location.href='?go=vnetworks&sub=add'" style="cursor: pointer;"><?=$lang["adm"]["nw"]["add"];?></span>]</td>
    </tr>
    <?
  $sub = $_GET['sub'];
  if ($sub == "add")
  {
  		
		if (isset($_POST['addnw']))
		{
			
			if (!empty($_POST['name']) && !empty($_POST['server']))
			{
			
				$sp = explode(":" , $_POST['server']);
				$port = (is_numeric($sp[1])) ? $sp[1] : 6667;
				
				$sql->query("INSERT INTO ".prfx."ircnetworks VALUES ('', '".$_POST['name']."', '".$sp[0]."', '".$port."')");
				
				header("Location: ?go=vnetworks&sub=uebersicht");
			}
			else {
				echo $lang["adm"]["nw"]["allfields"];
			}		
		}
	
	?>
    <tr>
      <td width="250" class="tleft"><?=$lang["adm"]["nw"]["name"];?></td>
      <td class="tright"><input type="text" name="name" id="name"></td>
    </tr>
    <tr>
      <td width="250" class="tleft"><?=$lang["adm"]["nw"]["srvport"];?></td>
      <td class="tright"><input type="text" name="server" id="server"></td>
    </tr>
    <tr>
      <td width="250" class="tleft">&nbsp;</td>
      <td class="tright">&nbsp;</td>
    </tr>
    <tr>
      <td width="250" class="tleft">&nbsp;</td>
      <td class="tright"><input type="submit" name="addnw" value="<?=$lang["adm"]["button"]["edit"];?>"></td>
    </tr>
    <?php
  }
  elseif ($sub == "edit" && is_numeric($_GET['id']))
  {

	$q = $sql->query("SELECT * FROM ".prfx."ircnetworks WHERE NETWORK_ID = ".$_GET['id']);
	
	$n = $sql->nums($q);
	
	if ($n != 1) { die("Diese Netzwerk existiert nicht."); }
	
	if (isset($_POST["editnw"]))
	{
		if ($_POST['del'] == 1)
		{
			$sql->query("DELETE FROM ".prfx."ircnetworks WHERE NETWORK_ID = ".$_GET['id']);
			
			header("Location: index.php?go=vnetworks");
		}
		else {
		
			$sp = explode(":" , $_POST['server']);
			$port = (is_numeric($sp[1])) ? $sp[1] : 6667;
			
			$sql->query("UPDATE ".prfx."ircnetworks SET
						 NETWORK_NAME = '".$_POST['name']."',
						 NETWORK_PORT = '".$port."',
						 NETWORK_SERVER = '".$sp[0]."'
						 WHERE NETWORK_ID = ".$_GET['id']);
			
			header("Location: index.php?go=vnetworks&sub=edit&id=".$_GET['id']);
		}
	
	}
	
	while ($r = $sql->content($q))
	{
	?>
    <tr>
      <td width="250" class="tleft"><?=$lang["adm"]["nw"]["name"];?></td>
      <td class="tright"><input type="text" name="name" id="name" value="<?=$r["NETWORK_NAME"];?>"></td>
    </tr>
    <tr>
      <td width="250" class="tleft"><?=$lang["adm"]["nw"]["srvport"];?></td>
      <td class="tright"><input type="text" name="server" id="server" value="<?=$r["NETWORK_SERVER"];?>:<?=$r["NETWORK_PORT"];?>"></td>
    </tr>
    <tr>
      <td class="tleft"><?=$lang["adm"]["nw"]["del"];?></td>
      <td class="tright"><input name="del" type="checkbox" id="del" value="1"></td>
    </tr>
    <tr>
      <td width="250" class="tleft">&nbsp;</td>
      <td class="tright">&nbsp;</td>
    </tr>
    <tr>
      <td width="250" class="tleft">&nbsp;</td>
      <td class="tright"><input type="submit" name="editnw" value="editieren"></td>
    </tr>
  <?php
  	}
  }
  else {
	  $q = $sql->query("SELECT * FROM ".prfx."ircnetworks");
	  while ($r = $sql->content($q))
	  {
  ?>
    <tr>
      <td width="250" class="tleft"><a href="?go=vnetworks&sub=edit&id=<?=$r["NETWORK_ID"];?>"><?=$r["NETWORK_NAME"];?></a></td>
      <td class="tright"><?=$r["NETWORK_SERVER"];?>
        :
          <?=$r["NETWORK_PORT"];?></td>
    </tr>
    <?
  	}
  }
  ?>
    <tr>
      <td colspan="2" class="tfoot">&nbsp;</td>
    </tr>
  </table>
</form>
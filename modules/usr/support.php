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

if (!defined("USER_ID")) die();
?>
<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="25%"><a href="index.php?go=usr&sub=support&do=new">Neues Ticket</a></td>
    <td width="25%"><a href="index.php?go=usr&sub=support&do=open">Offene Tickets</a></td>
    <td width="25%"><a href="index.php?go=usr&sub=support&do=close">Geschlossene Tickets</a></td>
    <td width="25%"><a href="index.php?go=usr&sub=support&do=archiv">Archiv</a></td>
  </tr>
</table>
<br />
<hr size="1" width="95%" noshade="noshade" />
<script type="text/javascript">

statchange = function (int)
{

	if (int == 1)
	{
		if (document.getElementById("all").checked == true)
		{
			document.getElementById("all").checked = false;
		}
	}
	else
	{
		if (document.getElementById("all").checked == true)
		{
		  for (var i = 0; i < document.form1.select2.length; i++) {
			document.form1.select2[i].checked = true;
		  }
		}
		else
		{
		  for (var i = 0; i < document.form1.select2.length; i++) {
			document.form1.select2[i].checked = false;
		  }
		}
	}
}
</script>

<br>
<?php
$do = $_GET['do'];

if ($do == "show" && is_numeric($_GET['sid']))
{

	$qry = $sql->query("SELECT * FROM ".prfx."support_questions WHERE SUPPORT_ID = ".$_GET['sid']." AND USER_ID = ".USER_ID);
	
	$n = $sql->nums($qry);
	
	if ($n != 1) echo "<font color=orange><strong>Meldung:</strong></font> {$lang['usr']['sup']['dntexist']}";
	else {
		
		$r = $sql->content($qry);
		
		echo '<table width="500" border="0" cellspacing="0" cellpadding="0">
		  <tr height="20">
			<td colspan="2" class="thead">'.$lang["usr"]["sup"]["sup"].': '.$r["SUPPORT_TOPIC"].'</td>
		  </tr>';
		  
		  $q = $sql->query("SELECT * FROM ".prfx."support_answers AS s
		  					LEFT JOIN ".prfx."users AS u ON u.USER_ID = s.USER_ID
							WHERE s.SUPPORT_ID = ".$r["SUPPORT_ID"]."
							ORDER BY SUPPORTA_ID ASC");
		  
		  $i=false;
		  while ($r = $sql->content($q))
		  {
			 echo ' <tr';
			 if ($i == true)
			 {
			 	echo " bgcolor=\"#E9EBF3\"";
				$i = false;
		     }
		 	 else $i=true;
			 
			 echo '>
				<td height="19" colspan="2" style="border-left: 1px solid #3382D9; border-right: 1px solid #3382D9; border-bottom: 1px solid #3382D9">
				<font style="font-size: 10px;">von '.$r["USER_VNAME"].' '.$r["USER_NNAME"].' am '.$r["SUPPORTA_DATE"].'</font>
				<hr size=1 noshade width=10% align=left>
				'.$bbcode->parse($r["SUPPORTA_TEXT"]).'
				</td>
			  </tr>
			';
		  }
		
		if (isset($_POST['as']) && !empty($_POST['text']))
		{
			
			$sql->query("INSERT INTO ".prfx."support_answers VALUES (
						 '',
						 '".$_GET['sid']."',
						 '".USER_ID."',
						 '".$_POST['text']."',
						 '".date("Y-m-d")."',
						 '".time()."')");
			
			$sql->query("UPDATE ".prfx."support_questions SET SUPPORT_ARCHIV = 0, SUPPORT_STATUS = 1 WHERE SUPPORT_ID = ".$_GET['sid']);
			
			header("Location: index.php?go=usr&sub=support&do=show&sid=".$_GET['sid']);
		
		}
		echo ' <tr>
					<td height="19" style="border-left: 1px solid #3382D9;"><br><strong>'.$lang["usr"]["sup"]["answr"].'</strong></td>
					<td style="border-right: 1px solid #3382D9; "></td>
				  </tr>
				<tr>
					<td height="19" style="border-left: 1px solid #3382D9;" valign="top">'.$lang["usr"]["sup"]["prob"].':</td>
					<td style="border-right: 1px solid #3382D9;">
						<form id="form1" name="form1" method="post" action="">
							<textarea name="text" style="width:250px; height: 150px;" id="text"></textarea>
							<br>
							<input type="submit" name="as" value="'.$lang["adm"]["button"]["add"].'" style="width:150px;" id="an" />
						</form>
					</td>
				</tr>
				<tr>
				  <td height="1" colspan="2" style="border-left: 1px solid #3382D9; border-right: 1px solid #3382D9; border-bottom: 1px solid #3382D9;">&nbsp;</td>
				</tr>
				';
	
		echo '</table>';
	}

}
elseif ($do == "open") {
	if (isset($_POST['do']))
	{
		if (is_array($_POST['select']) && $_POST['auswahl'] > 0)
		{
			
			if ($_POST['auswahl'] == 1)
			{
				foreach  ($_POST['select'] as $sel)
				{
					$sql->query("UPDATE ".prfx."support_questions SET SUPPORT_STATUS = 3 WHERE SUPPORT_ID = ".$sel." AND USER_ID = ".USER_ID);
				}
			}
			else
			{
				foreach ($_POST['select'] as $sel)
				{
					$sql->query("UPDATE ".prfx."support_questions SET SUPPORT_STATUS = 3, SUPPORT_ARCHIV = 1 WHERE SUPPORT_ID = ".$sel." AND USER_ID = ".USER_ID);
				
				}
			}
		}
	
	}
?>
<form name="form1" method="post" action="">
  <table width="600" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100" height="20"  class="thead"><div align="center"><?=$lang["usr"]["sup"]["ticket"];?></div></td>
      <td width="500"  class="thead"><?=$lang["usr"]["sup"]["bez"];?></td>
    </tr>
    <?php
	$qry = $sql->query("SELECT * FROM ".prfx."support_questions WHERE USER_ID = ".USER_ID." AND SUPPORT_STATUS < 3");

	while ($r = $sql->content($qry))
	{
	echo '<tr>
		<td style="border-left: 1px solid #3382D9;">
		<input type="checkbox" name="select[]" value="'.$r["SUPPORT_ID"].'" id="select2" onChange="statchange(\'1\');"> ticket'.$r["SUPPORT_ID"].'</td>
		<td style="border-right: 1px solid #3382D9;"><a href="index.php?go=usr&sub=support&do=show&sid='.$r["SUPPORT_ID"].'">'.$r["SUPPORT_TOPIC"].'</a></td>
	  </tr>';
	  echo "\n";
	 }
	  ?>
      <tr>
      <td style="border-left: 1px solid #3382D9;"><input type="checkbox" name="all" id="all" onChange="statchange('2');"></td>
      <td style="border-right: 1px solid #3382D9;">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" style="border-left: 1px solid #3382D9;border-right: 1px solid #3382D9; border-bottom: 1px solid #3382D9;">
      <select name="auswahl">
        <option value="-1"><?=$lang["usr"]["sup"]["choose"];?></option>
        <option value="1"><?=$lang["usr"]["sup"]["close"];?></option>
        <option value="2"><?=$lang["usr"]["sup"]["archv"];?></option>
      </select>
      <input type="submit" name="do" value="<?=$lang["usr"]["button"]["do"];?>"></td>
    </tr>
  </table>
</form>
<?php
}
elseif ($do == "close") {
	if (isset($_POST['do']))
	{
		if (is_array($_POST['select']) && $_POST['auswahl'] > 0)
		{
			
			foreach ($_POST['select'] as $sel)
			{
				$sql->query("UPDATE ".prfx."support_questions SET SUPPORT_STATUS = 3, SUPPORT_ARCHIV = 1 WHERE SUPPORT_ID = ".$sel." AND USER_ID = ".USER_ID);
			
			}
		}
	}
?>
<form name="form1" method="post" action="">
  <table width="600" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100" height="20"  class="thead"><div align="center"><?=$lang["usr"]["sup"]["ticket"];?></div></td>
      <td width="500"  class="thead"><?=$lang["usr"]["sup"]["bez"];?></td>
    </tr>
    <?php
	$qry = $sql->query("SELECT * FROM ".prfx."support_questions WHERE USER_ID = ".USER_ID." AND SUPPORT_STATUS > 2 AND SUPPORT_ARCHIV = 0");

	while ($r = $sql->content($qry))
	{
	echo '<tr>
		<td style="border-left: 1px solid #3382D9;">
		<input type="checkbox" name="select[]" value="'.$r["SUPPORT_ID"].'" id="select2" onChange="statchange(\'1\');"> ticket'.$r["SUPPORT_ID"].'</td>
		<td style="border-right: 1px solid #3382D9;"><a href="index.php?go=usr&sub=support&do=show&sid='.$r["SUPPORT_ID"].'">'.$r["SUPPORT_TOPIC"].'</a></td>
	  </tr>';
	  echo "\n";
	 }
	  ?>
      <tr>
      <td style="border-left: 1px solid #3382D9;"><input type="checkbox" name="all" id="all" onChange="statchange('2');"></td>
      <td style="border-right: 1px solid #3382D9;">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" style="border-left: 1px solid #3382D9;border-right: 1px solid #3382D9; border-bottom: 1px solid #3382D9;">
      <select name="auswahl">
        <option value="-1"><?=$lang["usr"]["sup"]["choose"];?></option>
        <option value="1"><?=$lang["usr"]["sup"]["archv"];?></option>
      </select>
      <input type="submit" name="do" value="<?=$lang["usr"]["button"]["do"];?>"></td>
    </tr>
  </table>
</form>
<?php
}
elseif ($do == "archiv") {
	if (isset($_POST['do']))
	{
		if (is_array($_POST['select']) && $_POST['auswahl'] > 0)
		{
			
			foreach ($_POST['select'] as $sel)
			{
				$sql->query("UPDATE ".prfx."support_questions SET SUPPORT_STATUS = 3, SUPPORT_ARCHIV = 1 WHERE SUPPORT_ID = ".$sel." AND USER_ID = ".USER_ID);
			
			}
		}
	}
?>
<form name="form1" method="post" action="">
  <table width="600" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100" height="20"  class="thead"><div align="center"><?=$lang["usr"]["sup"]["ticket"];?></div></td>
      <td width="500"  class="thead"><?=$lang["usr"]["sup"]["bez"];?></td>
    </tr>
    <?php
	$qry = $sql->query("SELECT * FROM ".prfx."support_questions WHERE USER_ID = ".USER_ID." AND SUPPORT_STATUS > 2 AND SUPPORT_ARCHIV = 1");

	while ($r = $sql->content($qry))
	{
	echo '<tr>
		<td style="border-left: 1px solid #3382D9;">
		ticket'.$r["SUPPORT_ID"].'</td>
		<td style="border-right: 1px solid #3382D9;"><a href="index.php?go=usr&sub=support&do=show&sid='.$r["SUPPORT_ID"].'">'.$r["SUPPORT_TOPIC"].'</a></td>
	  </tr>';
	  echo "\n";
	 }
	  ?>
    <tr>
      <td colspan="2" style="border-left: 1px solid #3382D9;border-right: 1px solid #3382D9; border-bottom: 1px solid #3382D9;">&nbsp;</td>
    </tr>
  </table>
</form>
<?php
}
else {

	if (isset($_POST['as']) && !empty($_POST['topic']) && !empty($_POST['text']))
	{
		
		$sql->query("INSERT INTO ".prfx."support_questions VALUES (
					 '',
					 '".USER_ID."',
					 '".$_POST['topic']."',
					 '0',
					 '0',
					 '0',
					 '".date("Y-m-d")."',
					 '".time()."',
					 '1')");
		
		$id = $sql->getid();
		
		$sql->query("INSERT INTO ".prfx."support_answers VALUES (
					 '',
					 '".$id."',
					 '".USER_ID."',
					 '".$_POST['text']."',
					 '".date("Y-m-d")."',
					 '".time()."')");

		header("Location: index.php?go=usr&sub=support&do=show&sid=".$id);
	
	}
?>
<form id="form1" name="form1" method="post" action="">
  <table width="500" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2" class="thead">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$lang["usr"]["sup"]["add"];?></td>
    </tr>
    <tr>
      <td width="152" height="19" style="border-left: 1px solid #3382D9;">&nbsp;</td>
      <td width="342" style="border-right: 1px solid #3382D9;">&nbsp;</td>
    </tr>
    <tr>
      <td style="border-left: 1px solid #3382D9;"><strong><?=$lang["usr"]["sup"]["kdescr"];?></strong></td>
      <td style="border-right: 1px solid #3382D9;"><input name="topic" type="text" style="width:250px;" id="topic" value="<?=$_POST['topic'];?>"></td>
    </tr>
    <tr>
      <td valign="top" style="border-left: 1px solid #3382D9;"><strong><?=$lang["usr"]["sup"]["pdescr"];?></strong></td>
      <td style="border-right: 1px solid #3382D9;"><textarea name="text" style="width:250px; height: 150px;" id="text"><?=$_POST['text'];?></textarea></td>
    </tr>
    <tr>
      <td style="border-left: 1px solid #3382D9; border-bottom: 1px solid #3382D9;">&nbsp;</td>
      <td style="border-right: 1px solid #3382D9; border-bottom: 1px solid #3382D9;"><input type="submit" name="as" value="<?=$lang["adm"]["button"]["add"];?>" style="width:150px;" id="an" /></td>
    </tr>
  </table>
</form>
  <?php
}
?>

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

/* modules/vs/news.php */

if (!defined("eggif")) header("Location: 404.html");
if (USER_STATUS != 2) header("Location: 404.html");
?>
<table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" class="thead">Nachrichten</td>
  </tr>
  <tr>
    <td width="200" class="tleft"><div align="center" class="eggm"><a href="?go=vsettings&sub=news&do=new">Neue Nachricht</a></div></td>
    <td class="tright"><div align="center" class="eggm">&nbsp;</div></td>
  </tr>
  <tr>
    <td colspan="2" class="tleft tright">&nbsp;</td>
  </tr>
  <?php
  $do = $_GET['do'];
  
  if ($do == "new")
  {
  	if (isset($_POST['Submit']) && !empty($_POST['headline']) && !empty($_POST['text']))
	{
		
		$sql->query("INSERT INTO ".prfx."news VALUES (
					 '',
					 '".USER_ID."',
					 '".time()."',
					 '".date("d.m.Y")."',
					 '".$_POST['headline']."',
					 '".$_POST['text']."','1')");
					 
		echo "<strong>News wurde erstellt.</strong>";
	
	}
  ?><tr>
    <td colspan="2" class="tleft tright">
  
<form name="form1" method="post" action="">
      &Uuml;berschrift:<br>
      <input type="text" name="headline" style="width: 500px;">
      <br>
Nachricht:<br>

        <textarea name="text" style="width: 500px; height: 300px;"></textarea>
        <br>
        <input type="submit" name="Submit" value="Senden">
    </form>
  </td>
  </tr>
  <?php
  }
  elseif ($do == "edit")
  {
  	if (isset($_POST['Submit']) && !empty($_POST['headline']) && !empty($_POST['text']))
	{
		
		$sql->query("UPDATE ".prfx."news SET
					 NEWS_HEADLINE = '".$_POST['headline']."',
					 NEWS_TEXT = '".$_POST['text']."'
					 WHERE NEWS_ID = '".$_GET['id']."'
					 ");

		if ($_POST['del'] == 1)
		{
		
			$sql->query("DELETE FROM ".prfx."news WHERE NEWS_ID = '".$_GET['id']."'");
			
			header("Location: index.php?go=vsettings&sub=news");
		
		}					 
	}
	
	$q = $sql->query("SELECT * FROM ".prfx."news WHERE NEWS_ID = '".$_GET['id']."'");
	$r = $sql->content($q);
	
  ?><tr>
    <td colspan="2" class="tleft tright">
  
<form name="form1" method="post" action="">
      &Uuml;berschrift:<br>
      <input type="text" name="headline" style="width: 500px;" value="<?=$sql->html($r["NEWS_HEADLINE"]);?>">
      <br>
Nachricht:<br>

        <textarea name="text" style="width: 500px; height: 300px;"><?=$sql->html($r["NEWS_TEXT"]);?></textarea>
        <br><label>
        <input name="del" type="checkbox" id="del" value="1">
        l&ouml;schen</label><br>
        <input type="submit" name="Submit" value="Senden">
    </form>
  </td>
  </tr>
  <?php
  }
  else {
  	
	$q = $sql->query("SELECT NEWS_HEADLINE, NEWS_ID FROM ".prfx."news");
	while ($r = $sql->content($q))
	{
		echo '<tr>
		 <td colspan="2" class="tleft tright"><a href="index.php?go=vsettings&sub=news&do=edit&id='.$r["NEWS_ID"].'">'.$sql->html($r["NEWS_HEADLINE"]).'</td>
		</tr>';
	}
  }
  ?>
  <tr>
    <td colspan="2" class="tfoot">&nbsp;
    </td>
  </tr>
</table>
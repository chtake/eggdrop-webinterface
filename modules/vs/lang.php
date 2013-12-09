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

/* modules/vs/lang.php */

if (!defined("eggif")) header("Location: 404.html");
if (USER_STATUS != 2) header("Location: 404.html");
?>

<table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" class="thead"><?=$lang["adm"]["lang"]["smg"];?></td>
  </tr>
  <tr>
    <td class="tleft tright">
<?php
if (isset($_POST['lnginst']) && !empty($_POST['lang'])) {
	$lang = explode("-",$_POST['lang']);
	if ($lang[1] == 0) {
		/* Language Datei downloaden */
		
		$dl_d = "http://".$cfg["LUSERVER"]."/files/".$lang[0].".lang.txt";
		$dl_z = "language/".$lang[0].".lang.php";
		
		$s_handle = fopen ($dl_d, "r");
		$d_handle = fopen ($dl_z, "w");

		$source = "";
		
		while (!feof($s_handle))
		{
			$source .= fgets($s_handle, 1024);
		}
		
		fwrite($d_handle, $source);
		
		fclose($s_handle);
		fclose($d_handle);

		if (is_file('language/'.$lang[0].'.lang.php'))
		{
			echo $lang["adm"]["lang"]["insne"];
		}
		else {
			echo $lang["adm"]["lang"]["inse"]." ".$lang[0];
		}
		
	} elseif ($lang[1] == 1) {
	if($_SESSION['language'] == $lang[0]) echo $lang["adm"]["lang"]["ddsyu"];
	else {
		@unlink('language/'.$lang[0].'.lang.php');
		
		if (!is_file('language/'.$lang[0].'.lang.php')) echo $lang["adm"]["lang"]["sed"];
		else echo $lang["adm"]["lang"]["ewd"].' '.$lang[0];
		}
	}
	echo "<meta http-equiv='refresh' content='0; url=index.php?go=vsettings&sub=lang'>";
}

$fp = fsockopen ($cfg["LUSERVER"], 80, $errno, $errstr, 30);
echo '<form id="form1" name="form1" method="post" action="">
<table width="330" border="0" cellspacing="0" cellpadding="0">';
if (!$fp) echo "<tr><td>$errstr ($errno)<br />\n</td></tr>";
else {
	$i = 1;
	
	$out = "GET / HTTP/1.0\r\n";
	$out .= "Host: ".$cfg["LUSERVER"]."\r\n";
	$out .= "Connection: Close\r\n\r\n";
	
	
	fputs ($fp, $out);
	while (!feof($fp)) {
		if ($i > 9) {
			$fgets = fgets($fp,1024);
			$fgets = explode("\n",$fgets);
			if (is_file('language/'.$fgets[0].'.lang.php')) {
				$stats = 1;
				$put = '<img src="gfx/icons/folder_del.jpg" width="32" height="32" />';
			} else {
				$stats = 0;
				$put = '<img src="gfx/icons/folder_add.jpg" width="32" height="32" />';
			}
			echo '<tr>
			<td width="168"><strong>'.$sql->html(ucfirst($fgets[0])).'</strong></td>
			<td width="72">'.$put.'</td>
			<td width="90"><input type="radio" name="lang" value="'.strtolower($fgets[0]).'-'.$stats.'" /></td>
			</tr>';
		} else fgets($fp,128);
		$i++;
	}
	$i = 1;
	fclose($fp);
	echo'<tr>
    <td colspan="3">&nbsp;</td>
	</tr>
	<tr>
	<td colspan="3"><input type="submit" name="lnginst" value="'.$lang["adm"]["button"]["dinstl"].'"/></td>
	</tr>
	</table>
	</form>';
}
?></td>
  </tr>
  <tr>
    <td class="tfoot">&nbsp;</td>
  </tr>
</table>

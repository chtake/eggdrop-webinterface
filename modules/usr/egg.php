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

/* modules/usr/egg.php */

/* For security issues */
if (!defined("eggif")) { header("Location: error404.html"); }

$id = $_GET['id'];

if (!is_numeric($id)) { die(); }

/* Abfrage in DB, nur für Admin oder Owner */
if (USER_STATUS == 2)
{
	$q = $sql->query("SELECT * FROM ".prfx."eggdrops AS e LEFT JOIN ".prfx."rootserver AS r ON r.ROOT_ID = e.ROOT_ID WHERE EGG_ID = '".$id."'");
}
else {
	$q = $sql->query("SELECT * FROM ".prfx."eggdrops AS e LEFT JOIN ".prfx."rootserver AS r ON r.ROOT_ID = e.ROOT_ID WHERE EGG_ID = '".$id."' AND USER_ID = '".USER_ID."'");
}

$n = $sql->nums($q);

if ($n != 1) { die($lang["usr"]["egg"]["ede"]); }

$r = $sql->content($q);

?>
<table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="4" class="thead"><?=$lang["usr"]["egg"]["econsole"];?></td>
    </tr>
<tr>
    <td width="25%" class="tleft"><div align="center" class="eggm"><a href="?go=usr&sub=egg&id=<?=$id;?>&mode=start"><?=$lang["usr"]["egg"]["start"];?></a></div></td>
    <td width="25%" class="tcontent"><div align="center" class="eggm"><a href="?go=usr&sub=egg&id=<?=$id;?>&mode=stop"><?=$lang["usr"]["egg"]["stop"];?></a></div></td>
    <td width="25%" class="tcontent"><div align="center" class="eggm"><a href="?go=usr&sub=egg&id=<?=$id;?>&mode=scripte"><?=$lang["usr"]["egg"]["scritps"];?></a></div></td>
    <td width="25%" class="tright"><div align="center" class="eggm"><a href="?go=usr&sub=egg&id=<?=$id;?>&mode=config"><?=$lang["usr"]["egg"]["config"];?></a></div></td>
</tr>
<tr>
  <td colspan="4" class="tleft tright">&nbsp;</td>
</tr>
<tr>
  <td colspan="4" class="tleft tright">
  <?php
  	$do = $_GET['mode'];
	
	if ($do == "config")
	{
		if (isset($_POST['changeconfig']))
		{
		
			$sql->query("UPDATE ".prfx."eggdrops SET
						EGG_CFG_USERNAME = '".$_POST['username']."',
						EGG_CFG_NICKNAME = '".$_POST['nickname']."',
						EGG_CFG_IDENT = '".$_POST['ident']."',
						EGG_CFG_ALTNICK = '".$_POST['altnick']."',
						EGG_CFG_ADMIN = '".$_POST['admin']."',
						EGG_CFG_CTCPVERSION = '".$_POST['ctcp']."',
						EGG_CFG_STDCHAN = '".$_POST['stdchan']."'
						WHERE EGG_ID = $id");
			
			if ($r["RI_IP"] != $_POST['nip'])
			{
			
				/*
				* IP organisieren
				*/
				
				$q43 = $sql->query("SELECT RI_IP, RI_HOST FROM ".prfx."rootserver_ips WHERE RI_IP = '".$_POST['nip']."'");
				$r43 = $sql->content($q43);
				
				$host = $r43["RI_HOST"];

				/*
				* ...
				*/
				
				$sql->query("UPDATE ".prfx."rootserver_ips SET RI_STAT_EGGS = RI_STAT_EGGS-1 WHERE RI_IP = '".$r["RI_IP"]."'");
				
				$sql->query("UPDATE ".prfx."eggdrops SET EGG_CFG_IP = '".$_POST['nip']."', RI_IP = '".$_POST['nip']."', EGG_CFG_HOSTNAME = '".$host."' WHERE EGG_ID = $id");

				$sql->query("UPDATE ".prfx."rootserver_ips SET RI_STAT_EGGS = RI_STAT_EGGS+1 WHERE RI_IP = '".$_POST['nip']."'");
			
			}
			
				$eggdrop->refreshConfig($id);
				
				header("Location: ?go=usr&sub=egg&id=$id&mode=config");
		}
	?>	
	<form name="form1" method="post" action="">
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td colspan="2"><strong><?=$lang["usr"]["egg"]["general"];?></strong></td>
          </tr>
        <tr>
          <td width="26%"><?=$lang["usr"]["egg"]["usrname"];?></td>
          <td width="74%"><input type="text" name="username" id="username" value="<?=$sql->html($r["EGG_CFG_USERNAME"]);?>" style="width: 250px;"></td>
          </tr>
        <tr>
          <td><?=$lang["usr"]["egg"]["nickname"];?></td>
          <td><input type="text" name="nickname" id="nickname" value="<?=$sql->html($r["EGG_CFG_NICKNAME"]);?>" style="width: 250px;"></td>
          </tr>
        <tr>
          <td><?=$lang["usr"]["egg"]["ident"];?></td>
          <td><input type="text" name="ident" id="ident" value="<?=$sql->html($r["EGG_CFG_IDENT"]);?>" style="width: 250px;" <?=($r["EGG_CFG_LOCKIDENT"] == 1) ? " readonly" : ""; ?>></td>
          </tr>
        <tr>
          <td><?=$lang["usr"]["egg"]["altnick"];?></td>
          <td><input type="text" name="altnick" id="altnick" value="<?=$sql->html($r["EGG_CFG_ALTNICK"]);?>" style="width: 250px;"></td>
          </tr>
        <tr>
          <td><?=$lang["usr"]["egg"]["adminame"];?></td>
          <td><input type="text" name="admin" id="admin" value="<?=$sql->html($r["EGG_CFG_ADMIN"]);?>" style="width: 250px;"></td>
          </tr>
        <tr>
          <td><?=$lang["usr"]["egg"]["ctcp"];?></td>
          <td><input type="text" name="ctcp" id="ctcp" value="<?=$sql->html($r["EGG_CFG_CTCPVERSION"]);?>" style="width: 250px;"></td>
          </tr>
        <tr>
          <td><?=$lang["usr"]["egg"]["stdchan"];?></td>
          <td><input type="text" name="stdchan" id="stdchan" value="<?=$sql->html($r["EGG_CFG_STDCHAN"]);?>" style="width: 250px;"></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          </tr>
        <tr>
          <td colspan="2"><strong><?=$lang["usr"]["egg"]["conns"];?></strong></td>
          </tr>
        <tr>
          <td><?=$lang["usr"]["egg"]["vhost"];?></td>
          <td><select name="nip" style="width: 250px;">
          	<?php
			
				$k=false;
				
				if ($r["EGG_PRIVVHOSTS"] == 2)
				{
					$q2 = $sql->query("SELECT * FROM ".prfx."eggdrops_hosts AS h
										LEFT JOIN ".prfx."rootserver_ips AS i
										ON i.RI_IP = h.RI_IP
										WHERE EGG_ID = $id AND RI_STAT_EGGS < RI_STAT_MAXEGGS");

					while ($r2 = $sql->content($q2))
					{
						
						echo '<option value="'.$sql->html($r2["RI_IP"]).'"';
						if ($r["RI_IP"] == $r2["RI_IP"]) { echo " selected"; $k=true; }
						echo '>'.$sql->html($r2["RI_HOST"]).'</option>';
						
					}
					
					if ($k == false)
					{
						echo '<option value="'.$sql->html($r["EGG_CFG_IP"]).'" selected>'.$sql->html($r["EGG_CFG_HOSTNAME"]).'</option>';
					}
					
				}
				elseif ($r["EGG_PRIVVHOSTS"] == 1)
				{
					$q2 = $sql->query("SELECT * FROM ".prfx."rootserver_ips WHERE ROOT_ID = ".$r["ROOT_ID"]." AND RI_STAT_EGGS < RI_STAT_MAXEGGS");

					while ($r2 = $sql->content($q2))
					{
						
						echo '<option value="'.$sql->html($r2["RI_IP"]).'"';
						if ($r["RI_IP"] == $r2["RI_IP"]) { echo " selected"; $k=true; }
						echo '>'.$sql->html($r2["RI_HOST"]).'</option>';
						
					}
					
					if ($k == false)
					{
						echo '<option value="'.$sql->html($r["EGG_CFG_IP"]).'" selected>'.$sql->html($r["EGG_CFG_HOSTNAME"]).'</option>';
					}
				}
				else
				{
					$q2 = $sql->query("SELECT * FROM ".prfx."rootserver_ips WHERE (ROOT_ID = ".$r["ROOT_ID"].") AND (RI_STAT_EGGS < RI_STAT_MAXEGGS) AND (RI_PUBLIC = 1)");

					while ($r2 = $sql->content($q2))
					{
						
						echo '<option value="'.$sql->html($r2["RI_IP"]).'"';
						if ($r["RI_IP"] == $r2["RI_IP"]) { echo " selected"; $k=true; }
						echo '>'.$sql->html($r2["RI_HOST"]).'</option>';
						
					}
					
					if ($k == false)
					{
						echo '<option value="'.$sql->html($r["EGG_CFG_IP"]).'" selected>'.$sql->html($r["EGG_CFG_HOSTNAME"]).'</option>';
					}
				}
			?>
          </select>          </td>
          </tr>
        <tr>
          <td><?=$lang["usr"]["egg"]["network"];?></td>
          <td><input type="text" name="ircn" id="ircn" value="<?=$sql->html($r["EGG_CFG_NETWORK"]);?>" style="width: 250px;" disabled></td>
          </tr>
        <tr>
          <td><?=$lang["usr"]["egg"]["iserver"];?></td>
          <td><input type="text" name="ircs" id="ircs" value="<?=$sql->html($r["EGG_CFG_SERVER"]);?>" style="width: 250px;" disabled></td>
          </tr>
        <tr>
          <td><?=$lang["usr"]["egg"]["iport"];?></td>
          <td><input type="text" name="ircp" id="ircp" value="<?=$sql->html($r["EGG_CFG_NETWORKPORT"]);?>" style="width: 250px;" disabled></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          </tr>
        <tr>
          <td colspan="2"><strong><?=$lang["usr"]["egg"]["ftpv"];?></strong></td>
          </tr>
        <tr>
          <td><?=$lang["usr"]["egg"]["ftpu"];?></td>
          <td><input type="text" name="ftpu" id="ftpu" value="<?=$sql->html($r["EGG_SC_FTPU"]);?>" style="width: 250px;" disabled></td>
          </tr>
        <tr>
          <td><?=$lang["usr"]["egg"]["ftpp"];?></td>
          <td><input type="text" name="ftpp" id="ftpp" value="<?=$sql->html($r["EGG_SC_FTPP"]);?>" style="width: 250px;" disabled></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td><input type="submit" name="changeconfig" value="ändern"></td>
          </tr>
      </table>
      </form>	
     <?php
	} 
	elseif ($do == "scripte")
	{
		
		if (isset($_POST['loadscripts']))
		{
			$lscript = $_POST['lscript'];
			
			$sql->query("DELETE FROM ".prfx."eggdrops_scripts WHERE EGG_ID = '".$id."'");
			
			foreach ($lscript as $s)
			{
				$sql->query("INSERT INTO ".prfx."eggdrops_scripts VALUES ('".$id."', '".$s."')");
			}
			
			$eggdrop->refreshConfig($id);
			
			header("Location: ?go=usr&sub=egg&id=$id&mode=scripte");
		
		}
		
		$ftp = new ftp();
		
		$ftp->connect($r["ROOT_SSH_IP"], $r["ROOT_FTP_PORT"], $r["EGG_FTP_USER"], $r["EGG_FTP_PASS"]);
		
		/* Script löschen */
		if (isset($_POST['delScript']) && !empty($_POST['script'])) {
			$ftp->delete($cfg["EGGSCRIPTDIR"].$_POST['script']);
		}
		
		/* Script hochladen */
		if (isset($_POST['hochladen'])) {
			$maxmb  = 5;
			$file_size = $_FILES["datei"]["size"] / 1024 / 1024;
			$fn = explode(".", $_FILES['datei']["name"]);
			$c = count($fn);
			$c--;
			if (strtoupper($fn[$c]) == "TCL") {
				if ($file_size <= $maxmb) {
					$datei=$_FILES["datei"]["tmp_name"];
					$ftp->put($cfg["EGGSCRIPTDIR"].$_FILES['datei']["name"], $datei);
					
				} else {
					echo "Ihr File beträgt mehr als $maxmb MB. Bitte laden Sie dieses Script über einen FTP-Client hoch.";
				}
			} else { echo "Sie können nur .tcl Files uploaden."; }
		}
		
		/* Ausgabe Scriptliste */
		$buffer = $ftp->getList($cfg["EGGSCRIPTDIR"]);
?>
	<strong><?=$lang["usr"]["egg"]["vscripts"];?>:</strong><br />
<form id="form1" name="form1" method="post" action="">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<?
	$q=0;
	for ($i=0;$i<count($buffer);$i++) {
		
		$b = explode(" ",$buffer[$i]);
		for ($x=0;$x<count($b);$x++) {

			$k = explode(".", $b[$x]);
			$a = count($k);
			$a--;
			if (strtoupper($k[$a]) == "TCL") {
				if ($q>4) $q=0;
				if ($q==0) echo "<tr>";
				echo "<td width=\"20%\">";
				echo '<input type="radio" name="script" value="'.$b[$x].'"><a href="?go=usr&sub=egg&id='.$id.'&mode=scriptedit&script='.$b[$x].'">'.$b[$x].'<blockquote></blockquote>';
				echo "</td>";
				if ($q==4) echo "</tr>";
				$q++;
			}
		}
	}
	if ($q<5)
	{
		for($i=$q;$i<5;$i++)
		{
			echo "<td>&nbsp;</td>";
		}
		echo "</tr>";
	}
		
	?>
	</table>
	<br />
<?=$lang["usr"]["egg"]["sog"];?>: <?=$ftp->humanformat($ftp->getSize($cfg["EGGSCRIPTDIR"]));?>
<br />

<input name="delScript" type="submit" id="delScript" value="l&ouml;schen" />
</form>
	<br />
	<br />
	<strong><?=$lang["usr"]["egg"]["nsh"];?>:</strong>
	<form name="form2" method="post" enctype="multipart/form-data" action="">
		<input name="datei" type="file" size="40" maxlength="1000000" />
		<input name="hochladen" type="submit" value="Hochladen" />
	</form>
	<br />
<br />
<strong><?=$lang["usr"]["egg"]["sl"];?></strong>
<br />
    <form id="form3" name="form3" method="post" action="">
      <select name="lscript[]" size="10" multiple="multiple" style="width: 250px;">
	  <?php
	  	
		$script = array();
		$q = $sql->query("SELECT EGG_SCRIPT FROM ".prfx."eggdrops_scripts WHERE EGG_ID = $id");
		while ($r = $sql->content($q))
		{
			$script[$r["EGG_SCRIPT"]] = true;
		}
	  
	  	for ($i=0;$i<count($buffer);$i++) {
		
		$b = explode(" ",$buffer[$i]);
		for ($x=0;$x<count($b);$x++) {

			$k = explode(".", $b[$x]);
			$a = count($k);
			$a--;
			if (strtoupper($k[$a]) == "TCL") {
				echo '<option value="'.$b[$x].'"';
				echo ($script[$b[$x]] == true) ? " selected" : "";
				echo'>'.$b[$x].'</option>';
			}
		}
	}
	?>
        
      </select>
        <input type="submit" name="loadscripts" value="laden" />
    </form>
    <?php
		$ftp->quit();
	}
	elseif ($do == "scriptedit")
	{
	
		$ftp = new ftp();
		
		$ftp->connect($r["ROOT_SSH_IP"], $r["ROOT_FTP_PORT"], $r["EGG_FTP_USER"], $r["EGG_FTP_PASS"]);

		if (isset($_POST['saveScript']))
		{
		
				$file = "temp/".$id;
				$handle = fopen($file, 'w+');
				fwrite($handle, stripslashes($_POST['textfield']));
				
				$ftp->put($cfg["EGGSCRIPTDIR"].$_GET['script'], $file);				
		}
				
			@fopen("temp/".$id, "x+");
			$handle = fopen("temp/".$id, 'w');
			$remote_file = $cfg["EGGSCRIPTDIR"].$_GET['script'];
			
			$ftp->get($handle, $remote_file);
			$handle = fopen("temp/".$id, "r");
			$contents = fread($handle, filesize("temp/".$id));
		?>
		<form name="form2" method="post" action="">
		  <textarea name="textfield" id="textfield" style="width: 650px; height: 500px; overflow:scroll;"><?=htmlentities($contents);?></textarea>
                <br>
		        <input type="submit" name="saveScript" value="<?=$lang["usr"]["button"]["save"];?>">
		</form>
      <?php
			
			$ftp->quit();
			fclose($handle);
			unlink("temp/".$id);		
	}
	elseif ($do == "start")
	{
		$ssh->connect($r["ROOT_ID"]);
		$output = $ssh->ssh_exec('cd '.$cfg["EGGROOT"].$cfg["EGGSSSCRIPTDIR"].' && ./'.$cfg["EGGSSSCRIPT"].' start '.$id.' '.$r["EGG_CFG_IDENT"]);
		$ssh->quit();
		
		echo '<textarea name="textfield" id="textfield" style="width: 650px; height: 500px; overflow:scroll;">'.htmlentities($output).'</textarea>';
	?>
	
	
	<?php
	}
	elseif ($do == "stop")
	{
	
		$ssh->connect($r["ROOT_ID"]);
		$ssh->ssh_exec('cd '.$cfg["EGGROOT"].$cfg["EGGSSSCRIPTDIR"].' && ./'.$cfg["EGGSSSCRIPT"].' stop '.$id);
		$ssh->quit();
		
		echo "<br><strong>Eggdrop wurde gestoppt.</strong>";
	}
  ?></td>
  </tr>
   <tr>
      <td colspan="4" class="tfoot">&nbsp;</td>
    </tr>
  </table>
<?

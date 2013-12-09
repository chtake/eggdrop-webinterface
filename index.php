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

/* index.php */
require_once("kernel/config.php");
require_once("kernel/mysql.class.php");
require_once("kernel/users.class.php");
require_once("kernel/blowfish.class.php");
require_once("kernel/eggdrop.class.php");
require_once("kernel/ssh.class.php");
require_once("kernel/ftp.class.php");
require_once("kernel/bbcode.class.php");


/* For security issues */
define("eggif", true);

$sql = sql::getInstance();
$sql->connect();

$ssh = ssh::getInstance();

$users = new users();

$eggdrop = new eggdrop();

$users->isLoggedOn();

$bbcode = new bbcode();

/* language file*/
if (!defined("USER_ID")) { header("Location: login.php"); }

require_once("language/".$_SESSION['language'].".lang.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Eggdrop Webinterface - by codershell.org</title>
<style type="text/css">
<!--
body,td,th {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 13px;
	color: #333333;
}
body {
	background-color: #F3F3F3;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
select, textarea, input, file {
	font-family:Verdana;
	font-size:10px; color:#909090;
	text-decoration:none;
	border-color:#006699;
	border-style:solid;
	border-top-width:1px;
	border-right-width:1px;
	border-bottom-width:1px;
	border-left-width:3px;
	padding:2px;
	background:#FFFFFF;
	margin: 1px;
}
div.menu {
	position: absolute;
	left: 0px;
	width: 200px;
	height: 100%;
	background: #DAE8F8;
	border-right: 1px solid #666666;
	overflow: auto;
}
div.m_head {
	font-size: 16px;
	font-weight: bold;
	margin-left: 17px;
	padding-top: 5px;
	border-bottom: 1px solid #CCCCCC;
	margin-bottom: 20px;
}
div.menu_head {
	background: #006699;
	width: 180px;
	color: #FFFFFF;
	padding: 2px;
	cursor: pointer;
}
div.menu_bottom {
	background: #F3F3F3;
	border: 1px solid #006699;
	width: 182px;
}
div.menu_bottom:hover {
	background: #e5e5e5;
	border: 1px solid #006699;
	width: 182px;
}
div.content {
	left: 200px;
	position: absolute;
	height: 100%;
	padding-left: 20px;
	width: 800px;
	overflow: auto;
}
a#menu:link {
	color: #333333;
	text-decoration: none;
}
a#menu:visited {
	text-decoration: none;
	color: #333333;
}
a#menu:hover {
	text-decoration: none;
	color: #006699;
}
a#menu:active {
	text-decoration: none;
	color: #333333;
}

/* Tabellen Spaß */
.thead {
	background: #006699;
	width: 180px;
	color: #FFFFFF;
	padding: 2px;
}
.tleft {
	background: #F3F3F3;
	border-left: 1px solid #006699;
	padding-left: 2px;
}
.tcont {
	background: #F3F3F3;
	padding-left: 2px;
}
.tright {
	background: #F3F3F3;
	border-right: 1px solid #006699;
	padding-left: 2px;
}
.tfoot {
	background: #F3F3F3;
	border-right: 1px solid #006699;
	border-bottom: 1px solid #006699;
	border-left: 1px solid #006699;
}

/* Links */
a:link {
	color: #333333;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #333333;
}
a:hover {
	text-decoration: none;
	color: #666666;
}
a:active {
	text-decoration: none;
	color: #333333;
}
font.anzeige {
	font-size: 10px;
}

div.eggm {
	background: #F3F3F3;
	border-bottom: 1px dashed #0066CC;
}
div.eggm:hover {
	background: #e5e5e5;
	border-bottom: 1px dashed #0066CC;
}
-->
</style>

<script type="text/javascript">
//<![CDATA[

/* AJAX.js */
xmlreq = function (path, divid)
{
	path = "ajax.php"+path;
	
    var req = (window.XMLHttpRequest) 
               ?
               new XMLHttpRequest()
               :
               ((window.ActiveXObject)
               ?
               new ActiveXObject("Microsoft.XMLHTTP")
               :
               false
               );

    req.open("GET",path,true);
    req.onreadystatechange = function()
    {
        if (req.readyState==4)
        {
            if (req.status == 200)
            {
                var d = document.getElementById(divid);
                d.innerHTML = req.responseText;
            }
        }
    }
    req.send(null)
}
register_chkname = function ()
{
	var d = document.getElementById("nickname").value;
	
	xmlreq("?go=register&chkname="+d, "chkn");

}

hidemenu = function (id)
{

	if (document.getElementById("menu"+id).style.display == "none")
	{
		document.getElementById("menu"+id).style.display = "block";
		
		xmlreq("?go=hidemenu&id="+id, "temp");
	}
	else if (document.getElementById("menu"+id).style.display == "block")
	{
		document.getElementById("menu"+id).style.display = "none";
		
		xmlreq("?go=hidemenu&id="+id, "temp");
	}
	
}
//]]>
</script>
</head>

<body>
<!-- Menu bereich -->
<div id="menu" class="menu" align="center"><a href="http://www.codershell.org"><img src="gfx/logo.jpg" border="0" /></a>
<? 
if (USER_STATUS == 2)
{
?>
<!-- Admin Menüs -->
  <div class="menu_head" align="left" onclick="hidemenu('1');">
<?=$lang["adm"]["menu"]["usradmn"];?>
</div>
<div class="menu_bottom" align="left" id="menu1" style="display: <?=($_SESSION['hidemenu'][1] == true) ? " none" : " block";?>"><a href="?go=vuser&amp;sub=uebersicht" id="menu"><?=$lang["adm"]["menu"]["overview"];?></a><br />
<a href="?go=vuser&amp;sub=suche" id="menu"><?=$lang["adm"]["menu"]["usearch"];?></a><br />
<a href="?go=vuser&amp;sub=new" id="menu"><?=$lang["adm"]["menu"]["newusr"];?></a><br />
</div>

<br />

<div class="menu_head" align="left" onclick="hidemenu('2');"><?=$lang["adm"]["menu"]["eggadmn"];?></div>
<div class="menu_bottom" align="left" id="menu2" style="display: <?=($_SESSION['hidemenu'][2] == true) ? " none" : " block";?>">
<a href="?go=veggdrops&amp;sub=uebersicht" id="menu"><?=$lang["adm"]["menu"]["overview"];?></a><br />
<a href="?go=veggdrops&amp;sub=new" id="menu"><?=$lang["adm"]["menu"]["newegg"];?></a><br />
</div>

<br />

<div class="menu_head" align="left" onclick="hidemenu('3');"> <?=$lang["adm"]["menu"]["srvadmn"];?>
</div>
<div class="menu_bottom" align="left" id="menu3" style="display: <?=($_SESSION['hidemenu'][3] == true) ? " none" : " block";?>">
<a href="?go=vserver&amp;sub=uebersicht" id="menu"><?=$lang["adm"]["menu"]["overview"];?></a><br />
<a href="?go=vserver&amp;sub=new" id="menu"><?=$lang["adm"]["menu"]["newsrv"];?></a><br />
<a href="?go=vserver&amp;sub=stats" id="menu"><?=$lang["adm"]["menu"]["statistic"];?></a><br />
<a href="?go=vnetworks&amp;sub=uebersicht" id="menu">IRC Netzwerke</a><br />
</div>

<br />

<div class="menu_head" align="left" onclick="hidemenu('4');"> <?=$lang["adm"]["menu"]["support"];?>
</div>
<div class="menu_bottom" align="left" id="menu4" style="display: <?=($_SESSION['hidemenu'][4] == true) ? " none" : " block";?>">
<a href="?go=vsupport&amp;sub=open" id="menu"><?=$lang["adm"]["menu"]["supopened"];?></a><br />
</div>

<br />

<div class="menu_head" align="left" onclick="hidemenu('5');"> <?=$lang["adm"]["menu"]["settings"];?>
</div>
<div class="menu_bottom" align="left" id="menu5" style="display: <?=($_SESSION['hidemenu'][5] == true) ? " none" : " block";?>">
<a href="?go=vsettings&amp;sub=news" id="menu"><?=$lang["adm"]["menu"]["newsmng"];?></a><br />
<a href="?go=vsettings&amp;sub=stats" id="menu"><?=$lang["adm"]["menu"]["statistic"];?></a><br />
<a href="?go=vsettings&amp;sub=updatechk" id="menu"><?=$lang["adm"]["menu"]["updtcheck"];?></a><br />
<a href="?go=vsettings&amp;sub=lang" id="menu"><?=$lang["adm"]["menu"]["languages"];?></a>
</div>

<br />
<!-- 

	Admin Menu ::ende

    User Menu

-->
<?
}
?>
<div class="menu_head" align="left" onclick="hidemenu('99');"> <?=$lang["usr"]["menu"]["general"];?>
</div>
<div class="menu_bottom" align="left" id="menu99" style="display: <?=($_SESSION['hidemenu'][99] == true) ? " none" : " block";?>">
<a href="?go=usr&amp;sub=news" id="menu"><?=$lang["usr"]["menu"]["news"];?></a><br />
<a href="?go=usr&amp;sub=profil" id="menu"><?=$lang["usr"]["menu"]["profil"];?></a><br />
<a href="?go=usr&amp;sub=support" id="menu"><?=$lang["usr"]["menu"]["support"];?></a><br />
<a href="?go=usr&amp;sub=infos" id="menu"><?=$lang["usr"]["menu"]["infos"];?></a><br />
</div>

<br />

<div class="menu_head" align="left" onclick="hidemenu('98');"> <?=$lang["usr"]["menu"]["eggdrops"];?>
</div>
<div class="menu_bottom" align="left" id="menu98" style="display: <?=($_SESSION['hidemenu'][98] == true) ? " none" : " block";?>">
<?php
$q = $sql->query("SELECT EGG_ID FROM ".prfx."eggdrops WHERE USER_ID = '".USER_ID."' ORDER BY EGG_ID ASC");
$i=1;
while ($r = $sql->content($q))
{
	echo '<a href="?go=usr&amp;sub=egg&amp;id='.$r["EGG_ID"].'" id="menu">Eggdrop #'.$i.'</a><br />';
	echo "\n";
	$i++;
}
?>
</div>

<!-- Menu Ende -->

</div>
<!-- Content bereich -->
<div id="content" class="content">
<?=$lang["usr"]["welcome"];?>, <strong><?=USER_NICK;?></strong>!<br /><br />
  <font class="anzeige">Anzeige:</font><br />
  <a href="http://www.codershell.org" target="_blank"><img src="gfx/ch.png" border="0" /></a><br />
  <hr noshade="noshade" size="1" />
    <br />
    <?php
	
	$go = $_GET['go'];
	
	$go = preg_replace("/..\//si", "", $go);
	$go = "modules/".$go.".php";

	if (file_exists($go))
	{
		include($go);
	}
	else
	{
		header("Location: index.php?go=usr&sub=news");
	}
?>
  
</div>
</body>
</html>
<!-- 
Our Network: www.codershell.org
-->
<div id="temp"></div>

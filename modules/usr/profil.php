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

/* modules/usr/profil.php */

/* For security issues */
if (!defined("eggif")) { header("Location: error404.html"); }

if ($_GET['logout'] == "true")
{
	session_destroy();
	header("Location: login.php");
}

$q = $sql->query("SELECT * FROM ".prfx."users WHERE USER_ID = '".USER_ID."'");

$r = $sql->content($q);
?>
<table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" class="thead">Profil</td>
  </tr>
  <tr>
    <td class="tright tleft"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="31%">User ID</td>
        <td width="69%"><?=$r["USER_ID"];?></td>
      </tr>
      <tr>
        <td>Nickname</td>
        <td><?=$r["USER_NICKNAME"];?></td>
      </tr>
      <tr>
        <td>Nachname</td>
        <td><?=$r["USER_NNAME"];?></td>
      </tr>
      <tr>
        <td>Vorname</td>
        <td><?=$r["USER_VNAME"];?></td>
      </tr>
      <tr>
        <td>Straße</td>
        <td><?=$r["USER_STRASSE"];?></td>
      </tr>
      <tr>
        <td>PLZ</td>
        <td><?=$r["USER_PLZ"];?></td>
      </tr>
      <tr>
        <td>Ort</td>
        <td><?=$r["USER_ORT"];?></td>
      </tr>
      <tr>
        <td>Eggdrops</td>
        <td><?=$r["USER_STAT_EGGS"];?></td>
      </tr>
      <tr>
        <td>Letzer Login</td>
        <td><?=date("d.m.Y", $r["USER_STAT_LLOGIN"]);?></td>
      </tr>
      <tr>
        <td>Logins</td>
        <td><?=$r["USER_STAT_LOGINS"];?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><a href="index.php?go=usr&sub=profil&logout=true">Logout</a></td>
        <td>&nbsp;</td>
      </tr>

    </table></td>
  </tr>
  <tr>
    <td class="tfoot">&nbsp;</td>
  </tr>
</table>
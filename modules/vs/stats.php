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

/* modules/vsupport.php */


if (!defined("eggif")) header("Location: 404.html");
if (USER_STATUS != 2) header("Location: 404.html");

$q = $sql->query("SELECT ROOT_ID FROM ".prfx."rootserver");
$server = $sql->nums($q);

$q = $sql->query("SELECT USER_ID FROM ".prfx."users");
$users = $sql->nums($q);

$q = $sql->query("SELECT EGG_ID FROM ".prfx."eggdrops");
$eggs = $sql->nums($q);

$version = $cfg["version"].".".$cfg["build"];;
?>
<table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" class="thead">Statistik</td>
  </tr>
  <tr>
    <td class="tright tleft"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="12%">Server</td>
        <td width="88%"><?=$server;?></td>
      </tr>
      <tr>
        <td>User</td>
        <td><?=$users;?></td>
      </tr>
      <tr>
        <td>Eggdrops</td>
        <td><?=$eggs;?></td>
      </tr>
      <tr>
        <td>Version</td>
        <td><?=$version;?></td>
      </tr>

    </table></td>
  </tr>
  <tr>
    <td class="tfoot">&nbsp;</td>
  </tr>
</table>
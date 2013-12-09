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

/* modules/usr/news.php */

/* For security issues */
if (!defined("eggif")) { header("Location: error404.html"); }

if (!defined("eggif_usr")) { header("Location: error404.html"); }

$q = $sql->query("SELECT * FROM ".prfx."news AS n
				  LEFT JOIN ".prfx."users AS u ON u.USER_ID = n.USER_ID
				  ORDER BY NEWS_ID DESC LIMIT 10");

while ($r = $sql->content($q))
{
	echo '<table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" class="thead"><b>'.$sql->html($r["NEWS_HEADLINE"]).'</b></td>
  </tr>
    <tr>
      <td class="tright tleft"><font style="font-size: 9px;">von '.$r["USER_VNAME"].' ' .$r["USER_NNAME"].' am '.date("d.m.Y", $r["NEWS_TIME"]).'</font><br><br>
'.$bbcode->parse($r["NEWS_TEXT"]).'</td>
    </tr>
    <tr>
    <td class="tfoot">&nbsp;</td>
  </tr></table><br><br>';
}
?>
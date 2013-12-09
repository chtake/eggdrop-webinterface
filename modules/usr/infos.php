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

/* modules/usr/infos.php */

/* For security issues */
if (!defined("eggif")) { header("Location: error404.html"); }
?>
<table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="100%" class="thead">Informationen zur Software</td>
  </tr>
    <tr>
      <td class="tright tleft"><br>
            <strong>E</strong>ggdrop <strong>W</strong>ebinterface <strong>V</strong>ersion 1.0<br>
Release Date: 05.05.2008<br>
<br>
© 2008 by Eric '<strong>take</strong>' Kurzhas - <a href="http://www.codershell.org">www.<strong>codershell</strong>.org</a>
<br>
<br>
Interface tested on Debian Etch network install. HowTo: www.codershell.org/board/<br />
<br />

Language Pack by: <?=$lang["usr"]["packby"];?>
</td>
    </tr>
    <tr>
    <td class="tfoot">&nbsp;</td>
  </tr>
</table>

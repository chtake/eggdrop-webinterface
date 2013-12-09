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

/* modules/vsettings.php */

if (!defined("eggif")) header("Location: 404.html");
if (USER_STATUS != 2) header("Location: 404.html");

$sub = $_GET['sub'];

switch ($sub)
{
	case "lang": include("modules/vs/lang.php"); break;
	case "news": include("modules/vs/news.php"); break;
	case "stats": include("modules/vs/stats.php"); break;
	case "updatechk" : include("modules/vs/updatechk.php"); break;
}

?>
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

/* modules/vs/updatechk.php */

if (!defined("eggif")) header("Location: 404.html");
if (USER_STATUS != 2) header("Location: 404.html");

$fp = fsockopen ($cfg["UPSERVER"], 80, $errno, $errstr, 30);
if (!$fp) { echo "No route to host."; }
else {
	$i = 1;
	
	$out = "GET /index.php HTTP/1.0\r\n";
	$out .= "Host: ".$cfg["UPSERVER"]."\r\n";
	$out .= "Connection: Close\r\n\r\n";
	
	fputs ($fp, $out);
	$i=0;
	while (!feof($fp)) {
		$fgets = fgets($fp,128);
		$fgets = explode("\n",$fgets);
			if ($i == 9)
			{
				$v = $fgets[0];
			}
			elseif ($i == 10) { $b = $fgets[0]; break; }
		$i++;
	}
	
	echo "Newest Version: ".$v.".".$b."<br>";
	echo "Installed Version: ".$cfg["version"].".".$cfg["build"];
	
	if ($b > $cfg["build"])
	{
		echo "<br><br><b>Please check <a href='http://www.codershell.org'>www.codershell.org</a> for updates.</b>";
	}
}
?>

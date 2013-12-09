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

/* kernel/ftp.class.php */

class ftp
{
	private $sql;
	private $con;

	/* Konstruktor, Instanz der DB Class einholen */
	function __construct()
	{
		$this->sql = sql::getInstance(); 
	}

	/* FTP Verbindung aufbauen .. */
	public function connect ($host, $port, $user, $pass)
	{
	
		$this->con = ftp_connect($host, $port, 5);
		
		$login = ftp_login ($this->con, $user, $pass);
	
		if ((!$this->con) || (!$login)) die("Unable to connect to the Hostsystem.");

		ftp_pasv ($this->con, true) ;

		return true;
	}
	
	/* RAW Liste ausgeben */
	public function getList ($dir)
	{
	
		$return = ftp_rawlist($this->con, $dir);
		
		return $return;
	}
	
	/* Files löschen */
	public function delete ($file)
	{
		
		ftp_delete($this->con, $file);
		
		return true;
	}
	
	/* Files raufladen */
	public function put ($nfile, $tmpfile)
	{
	
		ftp_put($this->con, $nfile, $tmpfile, FTP_ASCII);
		
		return true;
	}
	
	/* Speicherplatz Berechnung */
	public function getSize ($dir)
	{
		$temp = ftp_rawlist ($this->con, "-alR $dir");
        foreach ($temp as $file){
            if (ereg ("([-d][rwxst-]+).* ([0-9]) ([a-zA-Z0-9]+).* ([a-zA-Z0-9]+).* ([0-9]*) ([a-zA-Z]+[0-9: ]*[0-9]) ([0-9]{2}:[0-9]{2}) (.+)", $file, $regs)){ 
                $isdir = (substr ($regs[1],0,1) == "d");
                if (!$isdir)
                    $size += $regs[5];
            }
        }
        return $size;
	}
	
	public function _chmod ($mod, $file)
	{
		ftp_site($this->con,"chmod {$mod} {$file}");
	
		return true;
	}
	/* Bytes in lesbaren Wert formatieren */
	public function humanformat ($bytes)
	{
		if ($bytes > pow(2,10)) {
			if ($bytes > pow(2,20)) {
			$size = number_format(($bytes / pow(2,20)), 2);
			$size .= " MB";
			return $size;
			}
			else {
				$size = number_format(($bytes / pow(2,10)), 2);
				$size .= " KB";
				return $size;
			}
		}
		else {
		$size = (string) $bytes . " Bytes";
			return $size;
		} 
	}
	
	/* Hole Dateien */
	public function get ($handle, $remotefile)
	{
	
		ftp_fget($this->con, $handle, $remotefile, FTP_ASCII, 0);
		
		return true;
	}
	
	public function quit ()
	{
	
		ftp_close($this->con);

		return true;
	}
}
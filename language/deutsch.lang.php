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

/* languages/deutsch.lang.php */

$lang = array();

// admin

//-> Menü
$lang["adm"]["menu"]["overview"]  = "Übersicht";
$lang["adm"]["menu"]["usearch"]   = "User suchen";
$lang["adm"]["menu"]["newusr"]    = "Neuen User anlegen";
$lang["adm"]["menu"]["usradmn"]   = "Userverwaltung";
$lang["adm"]["menu"]["eggadmn"]   = "Eggdropverwaltung";
$lang["adm"]["menu"]["newegg"]    = "Eggdrop anlegen";
$lang["adm"]["menu"]["search"]    = "Suchen";
$lang["adm"]["menu"]["srvadmn"]   = "Serververwaltung";
$lang["adm"]["menu"]["newsrv"]	  = "Neuen Server anlegen";
$lang["adm"]["menu"]["statistic"] = "Statistik";
$lang["adm"]["menu"]["support"]   = "Support";
$lang["adm"]["menu"]["supopened"] = "Offene Tickets";
$lang["adm"]["menu"]["suparchiv"] = "Archiv";
$lang["adm"]["menu"]["settings"]  = "Einstellungen";
$lang["adm"]["menu"]["newsmng"]   = "Newsmanagement";
$lang["adm"]["menu"]["updtcheck"] = "Updatecheck";
$lang["adm"]["menu"]["languages"] = "Sprachen";

//-> veggdrops

$lang["adm"]["ve"]["add"]         = "Eggdrop anlegen";
$lang["adm"]["ve"]["config"]      = "Eggdrop Konfiguration";
$lang["adm"]["ve"]["usrname"]     = "Username";
$lang["adm"]["ve"]["usrnick"]     = "Nickname";
$lang["adm"]["ve"]["vhosts"]      = "VHosts";
$lang["adm"]["ve"]["allg"]        = "Allgemein";
$lang["adm"]["ve"]["usr"]         = "User";
$lang["adm"]["ve"]["srv"]         = "Server";
$lang["adm"]["ve"]["vh2"]         = "Nur zugewießene";
$lang["adm"]["ve"]["vh1"]         = "private und öffentliche";
$lang["adm"]["ve"]["vh0"]         = "Nur öffentliche";
$lang["adm"]["ve"]["ident"]       = "Ident";
$lang["adm"]["ve"]["network"]     = "IRC-Netzwerk";
$lang["adm"]["ve"]["altnick"]     = "Alt. Nickname";
$lang["adm"]["ve"]["lident"]      = "Lock Ident";
$lang["adm"]["ve"]["admname"]     = "Adminname";
$lang["adm"]["ve"]["ctcp"]        = "CTCP-Replay";
$lang["adm"]["ve"]["stdchan"]     = "Standard Channel";
$lang["adm"]["ve"]["plfields"]    = "Pflichtfelder";
$lang["adm"]["ve"]["eedit"]       = "Eggdrop editieren";
$lang["adm"]["ve"]["suspended"]   = "Suspended";
$lang["adm"]["ve"]["eui"]         = "Eggdrop User Interface";
$lang["adm"]["ve"]["vh_verw"]     = "VHost Verwaltung";
$lang["adm"]["ve"]["vh_opool"]    = "Aus Öffentlichen Pool";
$lang["adm"]["ve"]["vh_ppool"]    = "Aus privatem Pool";
$lang["adm"]["ve"]["active"]      = "aktiv";
$lang["adm"]["ve"]["vhost"]       = "VHost";

//-> vserver

$lang["adm"]["vs"]["allfields"]    = "Sie müssen alle mit * gekennzeichneten Felder ausfüllen";
$lang["adm"]["vs"]["addsrv"]       = "Server eintragen";
$lang["adm"]["vs"]["allg"]         = "Allgemein";
$lang["adm"]["vs"]["hw"]           = "Hardware";
$lang["adm"]["vs"]["srvname"]      = "Servername";
$lang["adm"]["vs"]["cpu"]          = "Prozessor";
$lang["adm"]["vs"]["ram"]          = "Arbeitsspeicher";
$lang["adm"]["vs"]["extras"]       = "Extras";
$lang["adm"]["vs"]["conns"]        = "Verbindungen";
$lang["adm"]["vs"]["sshu"]         = "SSH User";
$lang["adm"]["vs"]["ftpp"]         = "FTP Port";
$lang["adm"]["vs"]["sshpass"]      = "SSH Passwort";
$lang["adm"]["vs"]["sshp"]         = "SSH Port";
$lang["adm"]["vs"]["infos"]        = "Ihr Server muss vorbereitet sein für die installation eines neuen Hostsystems. Es müssen alle Module etc. geladen sein. Mehr dazu siehe:";
$lang["adm"]["vs"]["sshi"]         = "SSH IP";
$lang["adm"]["vs"]["distri"]       = "Distribution";
$lang["adm"]["vs"]["pfl"]          = "Pflichtfelder";
$lang["adm"]["vs"]["infos2"]       = "Info: nach dem Klick auf Hinzufügen, wird automatisch ein Eggdrop Image angelegt. Sollte dies nicht der Fall sein, müssen Sie es per Hand anlegen. (siehe www.codershell.org)";
$lang["adm"]["vs"]["dexists"]      = "Dieser Server existiert nicht.";
$lang["adm"]["vs"]["srvedit"]      = "Server editieren";
$lang["adm"]["vs"]["ipvh"]         = "IP-Adressen / VHosts";
$lang["adm"]["vs"]["nip"]          = "Neue IP";
$lang["adm"]["vs"]["host"]         = "Hostname";
$lang["adm"]["vs"]["addip"]        = "IP Eintragen";
$lang["adm"]["vs"]["vhost"]        = "VHost";
$lang["adm"]["vs"]["pub"]          = "öffentlich";
$lang["adm"]["vs"]["ipdexists"]    = "Diese IP existiert nicht.";
$lang["adm"]["vs"]["editip"]       = "IP Adresse editieren";
$lang["adm"]["vs"]["ip"]           = "IP Adresse";
$lang["adm"]["vs"]["mconns"]       = "Max. Verbindungen";


//-> vuser

$lang["adm"]["us"]["uadd"]         = "Nutzer anlegen";
$lang["adm"]["us"]["nname"]        = "Nachname";
$lang["adm"]["us"]["vname"]        = "Vorname";
$lang["adm"]["us"]["nickname"]     = "Spitzname";
$lang["adm"]["us"]["street"]       = "Straße";
$lang["adm"]["us"]["plzort"]       = "PLZ / Ort";
$lang["adm"]["us"]["pass"]         = "Passwort";
$lang["adm"]["us"]["susp"]         = "Gesperrt?";
$lang["adm"]["us"]["members"]      = "Mitgliedschaft";
$lang["adm"]["us"]["nuser"]        = "normaler Nutzer";
$lang["adm"]["us"]["auser"]        = "Administrator";
$lang["adm"]["us"]["pfl"]          = "Pflichtfelder";
$lang["adm"]["us"]["name"]         = "Name";
$lang["adm"]["us"]["llogin"]       = "Letze Anmeldung";
$lang["adm"]["us"]["uedit"]        = "Nutzer bearbeiten";
$lang["adm"]["us"]["usearch"]      = "Nutzer Suche";
$lang["adm"]["us"]["sa"]           = "Ihre Suchabfrage brachte 0 Treffer.";

//-> vnetworks
$lang["adm"]["nw"]["ircnws"]       = "IRC - Networks";
$lang["adm"]["nw"]["add"]          = "hinzufügen";
$lang["adm"]["nw"]["allfields"]    = "Sie muessen alle Felder ausfuellen.";
$lang["adm"]["nw"]["name"]         = "Netzwerkname";
$lang["adm"]["nw"]["srvport"]      = "Server:Port";
$lang["adm"]["nw"]["del"]          = "löschen";

//-> Button Beschriftung
$lang["adm"]["button"]["edit"]     = "Editieren";
$lang["adm"]["button"]["aufschalt"]= "aufschalten";
$lang["adm"]["button"]["add"]      = "Hinzufügen";
$lang["adm"]["button"]["dinstl"]   = "Installieren / Deinstallieren";

$lang["usr"]["button"]["save"]     = "speichern";
$lang["usr"]["button"]["do"]	   = "Durchfuehren";
//-> Languagemanagement
$lang["adm"]["lang"]["smg"]        = "Sprachenmanagement";
$lang["adm"]["lang"]["insne"]      = "Sprachpacket erfolgreich installiert";
$lang["adm"]["lang"]["inse"]       = "Fehler beim Installieren des Sprachpackets";
$lang["adm"]["lang"]["ddsyu"]      = "Sie können nicht die Sprache deinstallieren, die Sie gerade benutzen!";
$lang["adm"]["lang"]["sed"]        = "Sprachpacket erfolgreich deinstalliert.";
$lang["adm"]["lang"]["ewd"]        = "Fehler beim Deinstallieren von Sprachpacket";

// user

//-> Menü
$lang["usr"]["menu"]["general"]   = "Allgemein";
$lang["usr"]["menu"]["news"]      = "News";
$lang["usr"]["menu"]["profil"]    = "Profil";
$lang["usr"]["menu"]["infos"]     = "Infos zur Software";
$lang["usr"]["menu"]["eggdrops"]  = "Eggdrops";
$lang["usr"]["menu"]["support"]   = "Support";

//-> egg

$lang["usr"]["egg"]["ede"]        = "Eggdrop existiert nicht.";
$lang["usr"]["egg"]["econsole"]   = "Eggdrop Konsole";
$lang["usr"]["egg"]["start"]      = "Start";
$lang["usr"]["egg"]["stop"]       = "Stop";
$lang["usr"]["egg"]["config"]     = "Konfiguration";
$lang["usr"]["egg"]["scritps"]    = "Scripte";
$lang["usr"]["egg"]["general"]    = "Allgemein";
$lang["usr"]["egg"]["usrname"]    = "Username";
$lang["usr"]["egg"]["nickname"]   = "Nickname";
$lang["usr"]["egg"]["ident"]      = "Ident";
$lang["usr"]["egg"]["altnick"]    = "Alt. Nickname";
$lang["usr"]["egg"]["adminame"]   = "Adminname";
$lang["usr"]["egg"]["ctcp"]       = "CTCP-Replay";
$lang["usr"]["egg"]["stdchan"]    = "Standard Channel";
$lang["usr"]["egg"]["conns"]      = "Verbindung";
$lang["usr"]["egg"]["vhost"]      = "VHost";
$lang["usr"]["egg"]["network"]    = "IRC Netzwerk";
$lang["usr"]["egg"]["iserver"]    = "IRC Server";
$lang["usr"]["egg"]["iport"]      = "IRC Server Port";
$lang["usr"]["egg"]["ftpv"]       = "FTP Verbindung";
$lang["usr"]["egg"]["ftpu"]       = "FTP Nutzer";
$lang["usr"]["egg"]["ftpp"]       = "FTP Passwort";
$lang["usr"]["egg"]["vscripts"]   = "Vorhandene Scripte";
$lang["usr"]["egg"]["sog"]        = "Scripts Ordner Größe";
$lang["usr"]["egg"]["nsh"]        = "Neues Script hochladen";
$lang["usr"]["egg"]["sl"]         = "Scripte laden";

//-> support
$lang["usr"]["sup"]["dntexist"]   = "Dieses Ticket existiert nicht.";
$lang["usr"]["sup"]["sup"]        = "Support";
$lang["usr"]["sup"]["answr"]      = "Antworten";
$lang["usr"]["sup"]["prob"]       = "Problem";
$lang["usr"]["sup"]["ticket"]	  = "Ticket";
$lang["usr"]["sup"]["bez"]        = "Bezeichnung";
$lang["usr"]["sup"]["choose"]	  = "Bitte auswählen";
$lang["usr"]["sup"]["close"]	  = "Ticket schließen";
$lang["usr"]["sup"]["archv"]      = "Ticket archivieren";
$lang["usr"]["sup"]["add"]		  = "Support Ticket erstellen";
$lang["usr"]["sup"]["kdescr"]	  = "Kurz Beschreibung";
$lang["usr"]["sup"]["pdescr"]	  = "Problembeschreibung";
//->content
$lang["usr"]["welcome"]           = "Willkommen zurück";
$lang["allg"]["yes"]              = "ja";
$lang["allg"]["no"]               = "nein";
$lang["allg"]["del"]              = "löschen";
$lang["usr"]["packby"]			  = "Sprachpacket wurde erstellt von take - www.codershell.org";
?>
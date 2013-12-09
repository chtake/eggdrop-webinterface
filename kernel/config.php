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

/* kernel/config.php */

ob_start();
session_start();


define("prfx", "prfx_");

$cfg=array();

/* Konfigurations Variablen */
$cfg["BLOWFISHKEY"] = "256910905"; # Blowfish Key
$cfg["EGGROOT"] = "/home/eggdrops/"; # Eggdrop Home Dir
$cfg["EGGIMAGEDIR"] = "image/"; # image Path (relativ)
$cfg["EGGIMAGE"] = "eggdrop.img.tar"; # image Datei
$cfg["EGGSCRIPTDIR"] = "scripts/"; # Eggdrops Script Ordner (relativ)
$cfg["EGGSSSCRIPTDIR"] = "script/"; # Shellscript Ordner für Befehle
$cfg["EGGSSSCRIPT"] = "eggdrop.sh"; # Shellscript
$cfg["EGGCFGFILE"] = "eggdrop.conf";
$cfg["LUSERVER"]   = "lang.ew.updates.codershell.net"; # Language Pack Update Server.
$cfg["UPSERVER"]   = "ew.updates.codershell.net"; # Update Server
$cfg["version"] = "1";
$cfg["build"]   = "0";

/* Eggdrop Config */
$cfg["EGGCFG"] = '
    # basic

      set username     "{|IDENT|}"
      set ident        "{|IDENT|}"

      set nick         "{|NICKNAME|}"	
      set altnick      "{|ALTNICK|}"

      set admin        "{|ADMIN|}"
      set realname     "{|REALNAME|}"

      set owner        "{|OWNER|}"
      set ctcp-version "{|CTCP|}"

    # general stuff

      set network    "{|NETWORK|}"
      set timezone   "EST"
      set offset     "-1"

# for that autochkbot-stuff     

      set my-hostname   "{|HOSTNAME|}"
      set my-ip         "{|IP|}"

      listen {|TELNETPORT|} all

      set userfile "${ident}.user" 
      set chanfile "${ident}.chan"
      set pidfile  "${ident}.pid"

    # modules

      set mod-path "modules/"

      # load

        # disabled {woobie seen transfer share compress filesys assoc wire}

      foreach {module} { dns channels server ctcp irc transfer share compress console seen blowfish assoc wire } {
          loadmodule $module
      }

    # channel

      channel add {|STDCHAN|} {}
 
      set ban-time     0
      set exempt-time  30
      set invite-time  60
      set force-expire 0
      set share-greet  0
      set use-info     1

      # global channel

        set global-flood-chan 0:0
        set global-flood-deop 0:0
        set global-flood-kick 0:0
        set global-flood-join 0:0
        set global-flood-ctcp 0:0
        set global-flood-nick 0:0
        set global-aop-delay  0:0

        set global-idle-kick        0
        set global-stopnethack-mode 0
        set global-revenge-mode     0
        set global-ban-time         0

        set global-exempt-time 60
        set global-invite-time 60

        set global-chanmode "nt"

        set global-chanset {

          +autoop         +autovoice        -protectfriends -protectops        -autohalfop
          -bitch          +cycle            -revenge        -revengebot
          -dontkickops    +dynamicbans      -secret         -seen
          +dynamicexempts +dynamicinvites   -shared         -statuslog
          -enforcebans    -greet            +userbans       +userexempts
          -inactive       -nodesynch        +userinvites    -protecthalfops

        }

      # connect

        bind evnt -|- init-server    evnt:init_server
        bind evnt -|- connect-server evnt:connect_server

        proc evnt:connect_server { type } {

            global botnick ident

            set file [open ~/.oidentd.conf "w"]
            puts $file "global { reply "$ident" }"
            close $file

        }

        proc evnt:init_server { type } {

            global botnick authname pass modex

            putquick "PRIVMSG Q@CServe.quakenet.org :AUTH $authname $pass"
            putquick "MODE $botnick +i-ws"

            if {$modex} {
                putquick "MODE $botnick +x"
            }

        }

    # log files

      set keep-all-logs      1
      set console            "mkcobxs"

      addlang                "german"

      set max-logs           5
      set max-logsize        1024
      set quick-logs         0

      logfile msbxckjo *     "logs/${username}.log"

      set log-time           1

      set logfile-suffix     ".%d%b%Y"

      set switch-logfiles-at 300
      set quiet-save         0
      set keep-all-logs      1

    # files and directories

      set sort-users     0

      set help-path     "help/"
      set temp-path     "/tmp"
      set motd          "text/motd"
      set telnet-banner "text/banner"

      set userfile-perm 0600

    # bot net

      set protect-telnet    0
      set dcc-sanitycheck   0
      set ident-timeout     5
      set require-p         0
      set open-telnets      0
      set stealth-telnets   0
      set use-telnet-banner 1
      set dcc-flood-thr     3 

      set telnet-flood     10:60

      set paranoid-telnet-flood  1
      set resolve-timeout        15

    # more advanced stuff 

      set ignore-time      10
      set hourly-updates   00

      set notify-newusers  "$owner"
      set default-flags    ""
      set whois-fields     "url birthday"

      set remote-boots     0
      set share-unlinks    0
      set die-on-sighup    0
      set die-on-sigterm   1

      # unbind dcc n tcl *dcc:tcl
      # unbind dcc n set *dcc:set

      unbind dcc n simul *dcc:simul

      set must-be-owner    1
      set max-dcc          50

      set dcc-portrange    1024:65535

      set enable-simul     1
      set allow-dk-cmds    0
      set dupwait-timeout  5

    # server 

      set default-port 6667

      set servers {
          {|IRCSERVER|}:{|IRCPORT|}
      }

      set keep-nick          1
      set use-ison           1
      set strict-host 0
      set quiet-reject       0
      set lowercase-ctcp     0
      set answer-ctcp        3

      set flood-msg          5:60
      set flood-ctcp         3:60

      set ctcp-mode          0

      set never-give-up      1
      set strict-servernames 0
      set server-cycle-wait  60
      set server-timeout     15
      set servlimit          0
      set check-stoned       1
      set use-console-r      0
      set debug-output       0
      set serverror-quit     1
      set max-queue-msg      300
      set trigger-on-ignore  0
      set double-mode        0
      set double-server      0
      set double-help        0
      set optimize-kicks     1
      set nick-len           15

    # irc

      set bounce-bans 1
      set bounce-modes 0
      set max-bans 20
      set max-modes 30
      set kick-fun 0
      set ban-fun 0
      set learn-users 0
      set wait-split 600
      set wait-info 180
      set mode-buf-length 200

      # unbind msg - hello *msg:hello
      # bind msg - myword  *msg:hello

      unbind msg - ident   *msg:ident
      unbind msg - addhost *msg:addhost

      set no-chanrec-info 0

    # irc module 

      # net-type 1 specific features (IRCnet)

        set bounce-exempts   0
        set bounce-invites   0
        set max-exempts      20
        set max-invites      20
        set use-exempts      0
        set use-invites      0
        set prevent-mixing   0

      # net-type 1 specific features (IRCnet)

        set kick-method    1
        set modes-per-line 6
        set include-lk     1
        set use-354        0
        set rfc-compliant  1

    # transfer 

      set max-dloads   3
      set dcc-block    0
      set copy-to-tmp  1
      set xfer-timeout 45

    # share 

      set allow-resync    1
      set resync-time     1500
      set private-global  0

      set private-globals "mnot"

      set private-user    0
      set override-bots   0

    # compress

      set share-compressed 1
      set compress-level 9

    # notes 

      set notefile   ".notes"

      set max-notes    50
      set note-life    15
      set allow-fwd     0
      set notify-users  0
      set notify-onjoin 1

      # load module

        # loadmodule notes

    # console

      set console-autosave 1
      set force-channel    0
      set info-party       0

    # extra module 

      # loadmodule woobie

    # scripts

	{|LOADSCRIPTS|}
';
?>
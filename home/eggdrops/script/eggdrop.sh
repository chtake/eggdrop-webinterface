#!/bin/bash

#;
#;Eggdrop:Webinterface
#;- Eggdrop webinterface to give your usern and/or customers control over there bots.
#;
#;Copyright (C) 2008 Eric 'take' Kurzhals
#;    
#;	www.codershell.org
#;
#;This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the #;Free Software Foundation; either version 3 of the License, or (at your option) any later version.
#;
#;This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
#;
#;You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses/>.
#;

IMAGE="/home/eggdrops/image/eggdrop.img.tar"
DIR="/home/eggdrops/egg$2/"
CFGFILE="eggdrop.conf"

case "$1" in
install)
useradd -u 50$2 -d /home/eggdrops/egg$2 -g egg -s /bin/bash -p $3 -m egg$2
useradd -d /home/eggdrops/egg$2/scripts -g egg -u 50$2 -s /bin/bash -p $4 -o eggs$2
su -s /bin/bash egg$2 - -c "tar -C /home/eggdrops/egg$2 -xf $IMAGE"
echo "Eggdrop created."
;;

delete)
killall -u egg$2
userdel -r egg$2
userdel -r eggs$2
;;


start)
STAT=`ps aux | grep -v grep | grep egg$2 | wc -l`
if [[ $STAT -eq 0 ]]
then

touch egg$2

STARTM=`ls -l $DIR | grep $3 | wc -l`

if [[ $STARTM -lt 2 ]]
then
cd $DIR && rm -r *.chan && rm -r *.user
echo "Erstelle Benutzerdateien"
`su egg$2 - -c " cd $DIR && ./eggdrop -m $CFGFILE" > /home/eggdrops/script/egg$2`
else
`su egg$2 - -c " cd $DIR && ./eggdrop" > /home/eggdrops/script/egg$2`
fi

echo "eggdrop started"
else
echo "Eggdrop already running."
fi
;;

stop)
killall -u egg$2
;;

status)
STAT=`ps aux | grep -v grep | grep egg$2 | wc -l`
if [[ $STAT -eq 0 ]]
then
echo "1"
else
echo "2"
fi
;;

create)
useradd -d /home/eggdrops/eggimage -m eggimage
su eggimage - -c "cd && wget ftp://ftp.eggheads.org/pub/eggdrop/source/1.6/eggdrop1.6.18.tar.gz"
su eggimage - -c "cd /home/eggdrops/eggimage && tar -xf eggdrop1.6.18.tar.gz"
su eggimage - -c "cd /home/eggdrops/eggimage/eggdrop1.6.18 && ./configure && make config && make && make install"
mkdir /home/eggdrops/image
chmod -R 0766 /home/eggdrops/image
chown -R eggimage:eggimage /home/eggdrops/image
su eggimage - -c "cd /home/eggdrops/eggimage/eggdrop && tar -cf /home/eggdrops/image/eggdrop.img.tar *"
chmod -R 0777 /home/eggdrops/image
userdel -r eggimage
;;

*)
echo "Usage: $0 {start|stop|status|install|delete} (Parameter)"
exit 1
;;

esac
exit 0

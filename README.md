Eggdrop Webinterface
====================

### Screenshots

[![Screenshots](http://www.kurzhals.info/wp-content/gallery/eggdrop-webinterface/thumbs/thumbs_1_user_suche.png)](http://www.kurzhals.info/wp-content/gallery/eggdrop-webinterface/1_user_suche.png)
[![Screenshots](http://www.kurzhals.info/wp-content/gallery/eggdrop-webinterface/thumbs/thumbs_2_new_user.png)](http://www.kurzhals.info/wp-content/gallery/eggdrop-webinterface/2_new_user.png)
[![Screenshots](http://www.kurzhals.info/wp-content/gallery/eggdrop-webinterface/thumbs/thumbs_3_rootserver_edit.png)](http://www.kurzhals.info/wp-content/gallery/eggdrop-webinterface/3_rootserver_edit.png)
[![Screenshots](http://www.kurzhals.info/wp-content/gallery/eggdrop-webinterface/thumbs/thumbs_4_rootserver_vhost_edit.png)](http://www.kurzhals.info/wp-content/gallery/eggdrop-webinterface/4_rootserver_vhost_edit.png)
[![Screenshots](http://www.kurzhals.info/wp-content/gallery/eggdrop-webinterface/thumbs/thumbs_5_eggdrop_anlegen.png)](http://www.kurzhals.info/wp-content/gallery/eggdrop-webinterface/5_eggdrop_anlegen.png)
[![Screenshots](http://www.kurzhals.info/wp-content/gallery/eggdrop-webinterface/thumbs/thumbs_6_eggdrop_edit.png)](http://www.kurzhals.info/wp-content/gallery/eggdrop-webinterface/6_eggdrop_edit.png)
[![Screenshots](http://www.kurzhals.info/wp-content/gallery/eggdrop-webinterface/thumbs/thumbs_7_eggdrop_user_config_edit.png)](http://www.kurzhals.info/wp-content/gallery/eggdrop-webinterface/7_eggdrop_user_config_edit.png)

### Disclaimer

Please note that the Code is (very) old - its from 2008 or so and could be not anymore 'State of the Art'. Use it on your own risk.

### Installation

####  **Webinterface Server (Web)**
    
  * **OpenSSL**
    
    `apt-get install openssl`

  * **LibSSH2**

    `$ wget http://downloads.sourceforge.net/libssh2/libssh2-0.18.tar.gz?modtime=1194781489&big_mirror=0`
    
    `$ tar -xf libssh2-0.18.tar.gz`
    
    `$ cd libssh2-0.18`
    
    `$ ./configure && make all install`

  * **PHPize**

    `apt-get install php5-dev`
    
  * **SSH2**
   
    `$ wget http://pecl.php.net/get/ssh2-0.11.tgz`

    `$ tar –xf ssh2-0.11.tgz`

    `$ cd ssh2-0.11`

    `$ phpize && ./configure --with-ssh2 && make`
    
  * **webinterface**
   
    Edit the `kernel/config.php` file and change the database data.

    `chmod +0777 temp/`


#### **Eggdrop Host**
  *  **vsftpd**
    
    `apt-get install gcc g++ make vsftpd`

  * **Edit the `/etc/vsftpd.conf` configuration 
    file:**
    
        # Allow anonymous FTP? (Beware – allowed by default if you comment this out).
        anonymous_enable=NO
        # Uncomment this to allow local users to log in.
        local_enable=YES
        #
        # Uncomment this to enable any form of FTP write command.
        write_enable=YES
        ascii_upload_enable=YES
        ascii_download_enable=YES
        chroot_local_user=YES

  * **Add the Usergroup for all Eggdrops**
  
    `groupadd egg`
    
    `vi /etc/ssh/sshd_config` and add the eggdrop group du DenyGroups => `DenyGroups egg`

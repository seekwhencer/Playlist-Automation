Playlist Automation
==================================================

What is this?
--------------------------------------
This is a php web app to generate smart playlists and start playlist by timetable.
Developed by using a Raspberry Pi 3 but usable on every Ubuntu / Debian platform.

Hardware
--------------------------------------
- Raspberry Pi 3
- External 2.5 Harddisk
- USB-Hub with 2.5 Amps Power

Software
--------------------------------------
- This Web App, Scripts and System Configuration
- Apache 2, Webserver
- PHP 5 for Apache
- MPD, Music Player Deamon
- MPC, Music Player Client
- Icecast 2, Streaming Server
- NTP, Time Server Client

Install all Dependencies
--------------------------------------
```bash
sudo apt-get install icecast2 mpd mpc apache2 php5 ntp
```

Create Folders an get repository
--------------------------------------
```bash
sudo mkdir /data
cd /data
sudo git clone https://github.com/seekwhencer/Playlist-Automation.git
sudo mv Playlist-Automation radio
sudo chown -R pi:pi /data
sudo chmod -R 776 /data
```

Install Harddrive
--------------------------------------
- Check device name

```bash
blkid
```

- Format disk (attention)
```bash
sudo mkfs.vfat /dev/??? -n drivename
```

- Create directories on SD
```bash
sudo mkdir /external
sudo mkdir /external/hdd
sudo chown -R pi:pi /external
```

- Edit radio fstab
```bash
sudo nano /data/radio/script/fstab/hdd
```

- Add edit line
```bash
/dev/sda1       /external/hdd   vfat    defaults,umask=000      0       0 
```

Configure Icecast
--------------------------------------
```bash
sudo nano /etc/icecast2/icecast.xml
```
Replace the hostename and passwords
```bash
<source-password>changeme</source-password>
<relay-password>changeme</relay-password>
<admin-password>changeme</admin-password>
<port>8000</port>
<hostname>changeme</hostname>
```
Reload Icecast Config
```bash
sudo /etc/init.d/icecast2 reload
```

Configure MPD
--------------------------------------
```bash
sudo mv /etc/mpd.conf /data/radio/scripts/conf/mpd.conf
sudo nano /data/radio/scripts/conf/mpd_playlist.conf
```
Use this
```bash
playlist_directory   "/data/radio/htdocs/data/playlist"
music_directory      "/external"
user                 "pi"
db_file              "/data/radio/script/conf/mpd.cache"
pid_file             "/data/radio/script/conf/mpd_playlist.pid"
buffer_before_play   "30%"
port                 "6600"

log_level            "default"
log_file             "/data/radio/script/conf/mpd_playlist.log"

bind_to_address      "127.0.0.1"

audio_output {
    type        "shout"
    encoding    "mp3"
    name        "NFS One Playlist"
    host        "localhost"
    port        "8000"
    mount       "/playlist"
    password    "changeme"
    bitrate     "128"
    format      "44100:16:2"
}

audio_output {
    type "alsa"
    name "fake out"
    driver "null"
}

```

Replace user to pi:pi in
```bash
sudo nano /etc/init.d/mpd
```

Disable MPD on system start
```bash
sudo update-rc.d mpd disable
```

Configure Apache
--------------------------------------
- Move site config to apache folder
```bash
sudo cp /data/radio/scripts/nfs.conf /etc/apache2/sites-enabled/servername.conf
```

- Edit site config
```bash
sudo nano /etc/apache2/sites-enabled/servername.conf
```

- Change
```bash
ServerName servername
```

- PHP.ini
```bash
sudo nano /etc/php5/apache2/php.ini
```

- Change
```bash
max_execution_time = 3600
short_open_tag = On
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT
date.timezone = Europe/Berlin
```

- Apache envvars
```bash
sudo nano /etc/apache2/envvars
```

- Change
```bash
export APACHE_RUN_USER=pi
export APACHE_RUN_GROUP=pi
```

- Active mod rewrite
```bash
sudo a2enmod rewrite
```

- Apache config
```bash
sudo nano /etc/apache2/apache.config
```

- Change
```bash
#<Directory />
#       Options FollowSymLinks
#       AllowOverride None
#       Require all denied
#</Directory>

#<Directory /usr/share>
#       AllowOverride None
#       Require all granted
#</Directory>

#<Directory /var/www/>
#       Options Indexes FollowSymLinks
#       AllowOverride None
#       Require all granted
#</Directory>

#<Directory /srv/>
#       Options Indexes FollowSymLinks
#       AllowOverride None
#       Require all granted
#</Directory>
```
```bash
memory_limit = 256M
```
```bash
max_execution_time
max_input_time
```

Cronjobs
--------------------------------------
```bash
sudo crontab -e
```
```bash
*/1 * * * * sh /data/radio/script/save_mpd_status.sh
*/1 * * * * sh /data/radio/script/schedule.sh servername
0 */1 * * * sh /data/radio/script/podcast.sh servername
```

Time with Internet Connection
--------------------------------------
```bash
sudo nano /etc/ntp.conf
```

Comment out the existing servers and add these:
```bash
server 0.de.pool.ntp.org
server 1.de.pool.ntp.org
server 2.de.pool.ntp.org
server 3.de.pool.ntp.org
```

```bash
sudo /etc/init.d/ntp restart
```

Startup
--------------------------------------
```bash
sudo nano /etc/rc.local
```

Add this BEFORE exit 0
```bash
sudo sh /data/radio/script/start.sh &
```

Now - reboot:
```bash
sudo shutdown -r now
```

Configure Web App
--------------------------------------
- Edit config app.php
```bash
sudo nano /source/conf/app.php
```
- Change
```bash
'page_name' => 'changeme', 
'page_claim' => 'Radio',
'path_data' => 'data/',
'station_config' => 'station_config.json',
'user_secret' => '123456',
```

Web App Urls
--------------------------------------
- Home Screen
```bash
http://yourhost/radio
```
```bash
- Login
http://yourhost/radio/login
```
```bash
- Show Edit
http://yourhost/radio/admin/show
```
```bash
- Scheduling Edit
http://yourhost/radio/admin/schedule
```
```bash
- Podcast Edit
http://yourhost/radio/admin/podcast
```
```bash
- Config Edit
http://yourhost/radio/admin/config
```
```bash
- Cronjobs
http://yourhost/radio/admin/schedule/cron
http://yourhost/radio/admin/podcast/cron
```
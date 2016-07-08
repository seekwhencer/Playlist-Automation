Playlist Automation
==================================================

What is this?
--------------------------------------
This is a php web app to generate smart playlists and start playlist by timetable.
Developed by using a Raspberry Pi 3 but usable on every Ubuntu / Debian platform.

The Software generates Playlists by use a folder structure on the harddisk, filled with mp3 files.
The Randomness will be affected by the age of the files, by a hot rotation of the newest files, and spots and podcast every nth tracks.
At the time only Deutschlandfunk / DRadio podcasts are usable. The Podcast files will be downloaded and stored on the harddisk.
And playlists or shows can be scheduled. They can start automatically by time and weekday.
All data will be stored in json files. No Database needed.

![ScreenShot](/screenshots/radio_home.png?raw=true "Home Screen")
![ScreenShot](/screenshots/radio_shows.png?raw=true "Show Screen")
![ScreenShot](/screenshots/radio_schedule.png?raw=true "Scheduling Screen")

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
`sudo apt-get install icecast2 mpd mpc apache2 php5 ntp`

Create Folders an get repository
--------------------------------------
```bash
sudo mkdir /data
cd /data
sudo git clone https://github.com/seekwhencer/Playlist-Automation.git
sudo mv Playlist-Automation radio

sudo mkdir /data/web
sudo mkdir /data/web/root
sudo mkdir /data/web/root/htdocs
sudo mkdir /data/web/home
sudo mkdir /data/web/home/htdocs

sudo chown -R pi:pi /data
sudo chmod -R 776 /data
```

Install Harddrive
--------------------------------------
- Check device name
Get the devicename from the plugged harddrive. It is important, to get the right one!
```bash
blkid
```

- Format disk (attention)
The vfat format is readable by Windows. Replace the ???? with your device name. It could be 'sda2' or so...
```bash
sudo mkfs.vfat /dev/???? -n drivename
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

- Edit line, replace sda1 with your right one device name
```bash
/dev/sda1       /external/hdd   vfat    defaults,umask=000      0       0 
```

Configure Icecast
--------------------------------------
```bash
sudo nano /etc/icecast2/icecast.xml
```
- Replace the hostename and passwords
```bash
<source-password>changeme</source-password>
<relay-password>changeme</relay-password>
<admin-password>changeme</admin-password>
<port>8000</port>
<hostname>changeme</hostname>
```
- Reload Icecast Config
```bash
sudo /etc/init.d/icecast2 reload
```

Configure MPD
--------------------------------------
```bash
sudo mv /etc/mpd.conf /data/radio/scripts/conf/mpd.conf
sudo nano /data/radio/scripts/conf/mpd_playlist.conf
```
- Use this
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

- Replace user to pi:pi in
```bash
sudo nano /etc/init.d/mpd
```

- Disable MPD on system start
```bash
sudo update-rc.d mpd disable
```

Configure Apache
--------------------------------------
- Move site config to apache folder
```bash
sudo cp /data/radio/scripts/conf/radio.conf /etc/apache2/sites-enabled/servername.conf
```

- Remove default apache host
```bash
sudo unlink /etc/apache2/sites-enabled/000-default.conf
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
Ramdisk
--------------------------------------
```bash
sudo mkdir /mnt/RAMDisk
sudo chown pi:pi -R /mnt/RAMDisk
sudo chmod 777 -R /mnt/RAMDisk
```

Cronjobs
--------------------------------------
```bash
sudo crontab -e
```
```bash
*/1 * * * * sh /data/radio/script/save_mpd_status.sh
*/1 * * * * sh /data/radio/script/save_icecast_status.sh servername
*/1 * * * * sh /data/radio/script/schedule.sh servername
*/1 * * * * sh /data/radio/script/update_stream_meta.sh servername
0 */1 * * * sh /data/radio/script/podcast.sh servername
```

One Cronjob writes every 5th second the player status in the file: data/playlist/now_playing_song.txt

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

Or set the Date and Time manually

service ntp stop
sudo ntpd -gq
sudo service ntp start

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
- Login
```bash
http://yourhost/radio/login
```
- Show Edit
```bash
http://yourhost/radio/admin/show
```
- Scheduling Edit
```bash
http://yourhost/radio/admin/schedule
```
- Podcast Edit
```bash
http://yourhost/radio/admin/podcast
```
- Config Edit
```bash
http://yourhost/radio/admin/config
```
- Cronjobs
```bash
http://yourhost/radio/admin/schedule/cron
http://yourhost/radio/admin/podcast/cron
```

Variable Config Defaults
--------------------------------------
- max_files_per_playlist | 1000
- max_playlist_entries | 1000
- now_playing_show | now_playing_show.txt
- now_playing_song | now_playing_song.txt
- path_data_playlist | data/playlist/
- path_data_podcast | data/podcast/
- path_data_schedule | data/schedule/
- path_data_show | data/show/
- path_data_station | data/station/
- path_intro | /external/hdd/radio/intro/
- path_music | /external/hdd/radio/music/
- path_podcast | /external/hdd/radio/podcast/
- path_spot | /external/hdd/radio/spot/
- playlist_name | playlist.m3u
- schedule_week | week.json
- station_name | stationname

Show Settings
--------------------------------------

- Name
- Folder
- Recursive
- Description
- Color
- No Shuffle

#### Hot Rotation

- Enable
- Only
- Latest Tracks
- At Beginning
- Shuffle Beginning
- Song Age Days
- Insert Multiplier

#### Podcast
- Folder
- Every Nth Song
- Offset
- Latest Tracks
- Random First

#### Spots
- Folder
- Every nth Song
- Offset
- Shuffle Beginning (if not order shuffle)
- Order
- Order By (if order)

#### Intro
- Folder
- Random

Grunt, Bower workflow (not a must, but it's better)
--------------------------------------

To work on the Software, use Grunt for building and Bower for the dependency management.

- Install Node Packet Manager
```bash
sudo apt-get install nodejs npm
```
- one time, for the first time
```bash
npm install bower -g
```
- change into directory
```bash
cd /data/radio
```
- Install Grunt Modules
```bash
npm install grunt --save-dev
npm install grunt-contrib-jshint --save-dev
npm install grunt-contrib-less --save-dev
npm install grunt-contrib-watch --save-dev
npm install grunt-bower-task --save-dev
npm install grunt-bowercopy --save-dev
npm install grunt-contrib-csslint --save-dev
npm install grunt-sync --save-dev
npm install grunt-google-fonts --save-dev

```
- Watch the src folder
```bash
grunt watch
```
- Export and fetch newest dependencies into the htdocs folder
```bash
grunt export
```

It is important to work on the src folder, not on the htdocs folder.
The grunt watch task syncs the src with the htdocs folder and overwrites every changes in htdocs.
If you're working on the sources, use the src folder and let a grunt watch task to do this job.
If no grunt watch task is running, you can use grunt export to sync manually.

Wifi Access Point with onboard Wifi (since Pi3)
--------------------------------------

To use the Web-App on a smartphone or tablet - set up a Wifi Access Point and connect to it.
Then call the URL: http://yourhost/radio.

To catch all hosts to 25.25.25.1, use the dnsmasq "address" parameter.
When you are connected to the access point, enter some url in the browser. You will be redirected to the unhidden main menu.

It exists the folder /data/web/root/htdocs - this is the catch all target and documentroot of port 80 (http://yourhost).
Simply a redirect from this to http://yourhost/home changes the base url.
In /data/web/home/htdocs are the unhidden main menu. On this page you will find some jump buttons:
radio home, radio admin (login), icecast status and the direct link to listen directly in the browser.

Actually there are no web data in this project for the unhidden radio pi menu!

- Edit network interfaces
```bash
sudo nano /etc/network/interfaces
```

- Use this for wlan0
```bash
allow-hotplug wlan0
auto wlan0
iface wlan0 inet static
 address 25.25.25.1
 netmask 255.255.255.0
 broadcast 25.25.25.255
 post-up sudo hostapd -B /data/radio/script/conf/hostapd.conf > /dev/null; sleep 5; sudo service dnsmasq restart &
 down sudo killall hostapd
```

- Install hostapd and dnsmasq
```bash
sudo apt-get install hostapd dnsmasq
```

- Disable hostapd service
```bash
sudo update-rc.d hostapd disable
sudo update-rc.d dnsmasq disable
```
- Edit hostapd.conf from this repo
```bash
sudo nano /data/radio/script/conf/hostapd.conf
```
- Change
```bash
wpa_passphrase
```
- Edit dnsmasq config
```bash
sudo nano /etc/dnsmasq.conf
```
- Change
```bash
interface=wlan0
dhcp-range=25.25.25.50,25.25.25.150,12h
address=/#/25.25.25.1
```

- Edit DHCPD config
```bash
sudo nano /etc/dhcpcd.conf
```

- Add on bottom
```bash
denyinterfaces wlan0
```

- Enable IP4 Forwarding on next reboot
```bash
sudo nano /etc/sysctl.conf
```

- Change (uncomment)
```bash
net.ipv4.ip_forward=1
```

- Remove default apache host
```bash
sudo unlink /etc/apache2/sites-enabled/000-default.conf
```

Finally
--------------------------------------
- Reboot
```bash
sudo reboot
```

How to update
--------------------------------------

- create backup folder for the first time
```bash
mkdir /data/backup
mkdir /data/backup/radio
mkdir /data/backup/radio/htdocs
mkdir /data/backup/radio/script
```
- backup the data from the webapp
```bash
cp -R /data/radio/htdocs/data /data/backup/radio/htdocs/data
cp -R /data/radio/script/conf /data/backup/radio/script/conf
```

- download fresh repository from github
```bash
cd /data
sudo git clone https://github.com/seekwhencer/Playlist-Automation.git
sudo mv radio radio_
sudo mv Playlist-Automation radio
```

- restore backup data
```bash
cp -R /data/backup/radio/htdocs/data /data/radio/htdocs/data 
cp -R /data/backup/radio/script/conf /data/radio/script/conf 
```


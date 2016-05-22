wget -qO- http://$1/radio/admin/schedule/cron &> /dev/null
sleep 2
cd /
if [ -f /data/radio/htdocs/data/playlist/playlist.m3u ]
then
    sudo mpc -p 6800 -h 127.0.0.1 crop
    sudo mpc -p 6800 -h 127.0.0.1 load playlist
    sudo mpc -p 6800 -h 127.0.0.1 crossfade 8
    sudo mpc -p 6800 -h 127.0.0.1 play 2
    sudo mpc -p 6800 -h 127.0.0.1 repeat   
    
    sleep 2
    
    sudo mv /data/radio/htdocs/data/playlist/playlist.m3u /data/radio/htdocs/data/playlist/playlist_latest.m3u
fi

wget -qO- http://127.0.0.1/radio/admin/schedule/cron &> /dev/null
sleep 2
if [ -f /data/radio/htdocs/data/playlist/playlist.m3u ]
then
    sudo mpc -p 6600 crop
    sudo mpc -p 6600 load playlist
    sudo mpc -p 6600 crossfade 8
    sudo mpc -p 6600 play 2
    
    sleep 2
    
    sudo mv /data/radio/htdocs/data/playlist/playlist.m3u /data/radio/htdocs/data/playlist/playlist_latest.m3u
fi

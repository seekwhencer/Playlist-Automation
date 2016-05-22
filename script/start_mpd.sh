sudo service mpd stop

if [ -f /data/radio/script/conf/mpd_playlist.pid ]
then
    mpd /data/radio/script/conf/mpd_playlist.conf --kill
fi

if [ -f /data/radio/script/conf/mpd_fallback.pid ]
then
    mpd /data/radio/script/conf/mpd_fallback.conf --kill
fi

echo ""
sudo /etc/init.d/icecast2 restart
echo ""

sleep 2

echo "Starting MPD Playlist"
mpd /data/radio/script/conf/mpd_playlist.conf &

sleep 2

echo "Starting MPD Fallback"
mpd /data/radio/script/conf/mpd_fallback.conf &

sleep 2
echo ""
echo "Updating Audio Files"
echo "this can take a while ..."
echo ""
sudo mpc -p 6600 -h 127.0.0.1 update --wait
sudo mpc -p 6600 -h 127.0.0.1 load playlist_latest
sudo mpc -p 6600 -h 127.0.0.1 crossfade 8
sudo mpc -p 6600 -h 127.0.0.1 play
sudo mpc -p 6600 -h 127.0.0.1 repeat
echo ""

#sleep 2

#sudo mpc -p 6800  -h 127.0.0.1 update --wait
#sudo mpc -p 6800  -h 127.0.0.1 load fallback
#sudo mpc -p 6800  -h 127.0.0.1 crossfade 8
#sudo mpc -p 6800  -h 127.0.0.1 play
#sudo mpc -p 6800  -h 127.0.0.1 repeat
#echo ""

#echo "preparing ffmpeg with 60 seconds preroll"
#sleep 60
#sudo sh /data/radio/script/ffmix.sh

echo ""
echo ""
echo ""
echo ""
echo "Radio Startup complete..."
echo ""
echo "Connect to this http:// IP :8080/playlist"
echo ""


clear
echo ""
echo ""
echo ""
echo ""
echo ""
echo "Mounting external Hard Disk"
sudo cp /etc/fstab /data/radio/script/fstab/backup
sudo cat /etc/fstab /data/radio/script/fstab/hdd > /data/radio/script/fstab/merge
sudo cp /data/radio/script/fstab/merge /etc/fstab
sudo mount /dev/sda2
sleep 2
sudo cp /data/radio/script/fstab/backup /etc/fstab
echo ""

sudo /etc/init.d/mpd start
sudo /etc/init.d/samba start

echo "Updating Audio Files"
echo "this can take a while ..."
echo ""

sudo mpc update --wait
echo ""
sudo mpc load playlist_latest
sudo mpc crossfade 8
echo ""
sudo mpc play


echo ""
echo ""
echo ""
echo ""
echo "Radio Startup complete..."
echo ""
echo "Connect to this http:// IP :8080/live"



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

sudo sh /data/radio/script/start_mpd.sh &

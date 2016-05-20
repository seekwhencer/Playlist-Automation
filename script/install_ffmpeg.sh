cd /usr/src
sudo rm -R ffmpeg
git clone git://source.ffmpeg.org/ffmpeg.git
cd ffmpeg
sudo ./configure --arch=armel --target-os=linux --enable-gpl --enable-libmp3lame --enable-nonfree
sudo make
sudo make install

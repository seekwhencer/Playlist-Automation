sudo killall ffmpeg
sudo ffmpeg \
 -i http://admin:marsbase@127.0.0.1:8000/playlist \
 -i http://admin:marsbase@127.0.0.1:8000/fallback \
 -muxpreload 10 \
 -c:a libmp3lame -b:a 128k \
 -id3v2_version 3 \
 -legacy_icecast 1 \
 -content_type audio/mpeg \
 -ice_name "NFS One Radio" \
 -filter_complex "[0:a][1:a]amerge=inputs=2,pan=stereo|c0<c0+c2|c1<c1+c3[aout]" -map "[aout]" \
 -f mp3 icecast://admin:marsbase@127.0.0.1:8000/listen \
 -y \
 -stats


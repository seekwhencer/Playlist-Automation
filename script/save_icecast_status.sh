#!/bin/sh
counter=0
sleep=2
times=30

echo $times

while true; do
  echo $counter
  if [ $counter -eq $times ]; then
    exit 1
  else
#    wget -O /data/radio/htdocs/data/playlist/now_icecast_status.json http://$1:8000/status-json.xsl &
    wget -O /mnt/RAMDisk/now_icecast_status.json http://$1:8000/status-json.xsl &
    counter=$((counter+1))
    sleep $sleep
  fi

done

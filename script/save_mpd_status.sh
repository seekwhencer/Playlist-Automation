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
    mpc > /data/radio/htdocs/data/playlist/now_playing_song.txt &
    counter=$((counter+1))
    sleep $sleep
  fi

done

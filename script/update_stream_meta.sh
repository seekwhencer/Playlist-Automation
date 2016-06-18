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
    wget -qO- http://$1/radio/admin/streammetacron &> /dev/null
    counter=$((counter+1))
    sleep $sleep
  fi
done

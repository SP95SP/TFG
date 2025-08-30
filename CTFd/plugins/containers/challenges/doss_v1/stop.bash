#!/bin/bash

mydir=$( cd ${0%/*} ; pwd )
cd $mydir
INSTANCENAME=$( cat _instance )
docker compose -p $INSTANCENAME down
echo "0" > counter.txt

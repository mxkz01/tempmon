#!/bin/bash
IP="$(hostname -I)"
MAC="$(cat /sys/class/net/eth0/address)"
QUEUEFILE=/opt/spl/queue.txt
DEBUGFILE=/dev/null
SENDURL=http://beta.darval.local/temperature/sendspl.php

echo ===================={ DEBUG for $IP}==================== >> $DEBUGFILE
echo `date` - Starting Upload Script >> $DEBUGFILE
echo ------------------------------ >> $DEBUGFILE  
echo `cat $QUEUEFILE` >> $DEBUGFILE
echo ------------------------------ >> $DEBUGFILE

RESULT=`curl -v --data-urlencode queue@$QUEUEFILE --data-urlencode station_code=$MAC --data-urlencode  station_ip=$IP $SENDURL`

echo $RESULT
echo $RESULT >> $DEBUGFILE

echo GREP SUCCESS - `echo $RESULT | grep SUCCESS` >> $DEBUGFILE

if `echo $RESULT | grep -q SUCCESS`;
then
        rm $QUEUEFILE
        echo Removed $QUEUEFILE >> $DEBUGFILE
fi

echo ------------------------------ >> $DEBUGFILE  
echo `cat $QUEUEFILE` >> $DEBUGFILE
echo ------------------------------ >> $DEBUGFILE

echo `date` - Completed Upload Script >> $DEBUGFILE

exit 0

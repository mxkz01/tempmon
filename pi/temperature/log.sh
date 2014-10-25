#!/bin/bash
DAY=`date +%Y/%m/%d`
LOGFILE=/opt/temperature/queue.txt

TEMP=`/usr/local/bin/temper-poll |grep Device|gawk '{print $3}'|sed 's/\(^[\.0-9]*\)[^.0-9]*/\1/g'`
WHEN=`date +%d-%m-%Y_%H:%M`

if [ -n "$TEMP" ]; then
        echo $WHEN $TEMP >> $LOGFILE
fi

exit 0

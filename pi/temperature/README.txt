=== Raspberry Pi Temperature Logging Scripts ===

For logging temperature we have been using a TEMPer USB Thermometer (http://pcsensor.com/index.php?_a=product&product_id=41 - also found on eBay)

Instructions for getting the thermometer working on the Raspberry Pi can be found here -> http://verahill.blogspot.com.au/2013/12/532-temper-temperature-monitoring-usb.html

We deviated from the tutorial after we were able to launch temper-poll and receive an output as a normal user. Our logging scripts as based on the ones found in the above tutorial - hats off to the genius who developed temper-poll and the other genius who mashed together the original logging script.

==============

log.sh - logs data from the USB thermometer to queue.txt
upload.sh - uploads the contents of queue.txt to the Dashboard via curl and a POST request

Remember to change the server address and file locations in each script to suit your environment

You can also turn on debugging in upload.sh by setting the DEBUGLOG location to /opt/temperature/debug.txt instead of /dev/null (adjust to suit your environment)

You will also need to make these scripts executable so they can run directly from cron

chmod +x log.sh
chmod +x upload.sh

The contents of our crontab for the above scripts are below -
# m h  dom mon dow   command
*/2   * * * *     /opt/temperature/log.sh
*/5 * * * *     /opt/temperature/upload.sh

We were logging the temperature every 2 minutes, but you can change it to every minute (replace */2 with *) or whatever cycle you want

We were uploading the temperature every 5 minutes, but you can change it to meet your requirements.
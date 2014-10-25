=== Raspberry Pi Temperature Logging Scripts ===

For logging sound pressure levels (SPL) we have been using a Wensn Digital Sound Level Meter (http://www.wensn.com/html_products/digital-Sound-level-meter-WS1361-17.html - also found on eBay)

Instructions, theory and the original code for getting the SPL meter working on the Raspberry Pi can be found here -> http://opensource.ebswift.com/RaspiMonitor/wensn/

We have used the provided python scripts in the above link for logging the SPL measurements, but made improvements - hats off to this genius.

==============

usbsplloop.py - logs data from the USB SPL meter to queue.txt
upload.sh - uploads the contents of queue.txt to the Dashboard via curl and a POST request

Remember to change the server address and file locations in each script to suit your environment

You can also turn on debugging in upload.sh by setting the DEBUGLOG location to /opt/spl/debug.txt instead of /dev/null (adjust to suit your environment)

You will also need to make the upload script executable so they can run directly from cron

chmod +x upload.sh

The contents of our crontab for the above scripts are below -
# m h  dom mon dow   command
@reboot python /opt/spl/usbsplloop.py &
*/5 * * * *     /opt/spl/upload.sh

We are reading the sound pressure level continuously and then logging an average of the min, max and average values each minute

We were uploading the SPL readings every 5 minutes, but you can change it to meet your requirements.
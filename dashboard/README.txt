=== Dashboard ===

This contains a quick Bootstrap interface to show all sensors in the database, and whether there is an issue (over or under set thresholds). All changes to parameters need to happen from within the database itself.

This also contains the submission scripts triggered from the Raspberry Pi nodes for submitting Temperature and SPL Data into the database.

The functions.inc.php file is shared amongst both the Dashboard itself and the scripts used for uploading data from the Raspberry Pi nodes.

We have implemented a caching feature to improve page load times, as when the database contains lots of log entries, the page loaded slowly for users (as the database queries aren't well optimized at the moment). The cron job grabs data from the database and places it into text files, then these text files are loaded by the user, instead of loading directly from the database.

==============

/cron/.htaccess - prevents access to this folder from everyone (it should only be triggered from cli)
/cron/cron.sh - the cron script that triggers cache generation for the Dashboard (so far)
/cron/dashboard_panels.php - loads the sensor data from the database and writes HTML output to dashboard_panels.txt, then moves file to main directory
/css/ - contains bootstrap css files (so far)
/fonts/ - contains bootstrap glyphicon fonts (so far)
/js/ - contains bootstrap js files (including jquery and others) and a copy of the highcharts graphing library (so far)
functions.inc.php - database configuration, functions used to generate HTML and interact with the database (from Dashboard and Raspberry Pi nodes)
index.php - used as a landing point for the Dashboard
sendspl.php - used as a landing point for SPL data submission from the Raspberry Pi nodes
sendtemp.php - used as a landing point for Temperature data submission from the Raspberry Pi nodes


Remember to change the folder location in the cron script

You will also need to make the cron script executable so they can run directly from cron

chmod +x cron.sh

The contents of our crontab for the above script is below -
# m h  dom mon dow   command
*/5 * * * * /var/www/html/temperature/cron/cron.sh

We were running the cron script every 5 minutes, but you can change it to meet your requirements.

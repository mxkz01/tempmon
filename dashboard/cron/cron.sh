#!/bin/sh
echo `date` - Starting Temperature Monitor Cron Job
echo `date` - Exporting data from dashboard_panels.php to dashboard_panels.txt
cd /var/www/html/temperature/cron/
php dashboard_panels.php > dashboard_panels.txt
echo `date` - Moving dashboard_panels.txt to main folder
mv dashboard_panels.txt ../dashboard_panels.txt
echo `date` - Complete

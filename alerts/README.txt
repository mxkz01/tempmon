=== Alerts ===

This is a really simple HTML table that shows a summary of all sensors and their current status. This was developed so we could configure Wormly (https://www.wormly.com/) to detect the word FAIL in the page and then trigger SMS alerts to advise that someone should have a look at the stats.

==============

functions.inc.php - database configuration, functions used to interact with the database
index.php - used to generate the HTML table and read from the database

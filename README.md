tempmon
=======

Temperature Monitoring Dashboard

=== Dashboard ===

This is the precursor to https://github.com/mxkz01/sitemon and contains a really quick Bootstrap interface to show all Sensors in the database, and whether there is an issue (over or under set thresholds). All changes to parameters need to happen from within the database itself.

This also contains the submission scripts triggered from the Raspberry Pi nodes for submitting Temperature and SPL Data into the database.

=== Alerts ===

This is a really simple HTML table that shows a summary of all sensors and their current status. This was developed so we could configure Wormly (https://www.wormly.com/) to detect the word FAIL in the page and then trigger SMS alerts to advise that someone should have a look at the stats.

=== Raspberry Pi Scripts ===

This is a collection of the bash and python scripts used on the Raspberry Pi to log temperature and SPL data, then submit to the Dashboard via curl when internet is available.

=== Database ===

This is a structural copy of the MySQL Database.

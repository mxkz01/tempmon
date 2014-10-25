=== Raspberry Pi Scripts ===

This is a collection of the bash and python scripts used on the Raspberry Pi to log temperature and SPL data, then submit to the Dashboard via curl when internet is available.

With both the temperature and SPL logging, we aren't advertising that the data is 100% accurate as the devices are reasonably cheap and therefore aren't perfectly calibrated, however they provide a good way for comparing data over time for a number of locations for a reasonable cost.

When setting up the Raspberry Pi, we found it best to delegate the Pi to either SPL or Temperature monitoring, as we found that additional modifications needed to happen for logging SPL broke the temper-poll application used for logging temperature.

Also we found that there was no way to determine the order which USB thermometer would read out the data when using temper-poll, so one reboot would log thermometer A as thermometer A in the database, and then after the next reboot or USB panic thermometer A would be logging to thermometer B in the database. We recommend only using one USB thermometer per Raspberry Pi for this reason (unless you can work out a way to identify each individual thermometer, as it seems they all have the same HID information because they're the same product).

==============

The folder structure for the scripts are as follows
/temperature is copy of what we have been placing in /opt/temperature for logging temperature.
/spl is a copy of what we have been placing in /opt/spl for logging SPLs.

Information for getting the scripts working can be found in their respective README.txt file.
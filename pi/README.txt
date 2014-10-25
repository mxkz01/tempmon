=== Raspberry Pi Scripts ===

This is a collection of the bash and python scripts used on the Raspberry Pi nodes to log temperature and SPL data, then submit to the Dashboard via curl when internet is available.

For logging temperature we have been using a TEMPer USB Thermometer (http://pcsensor.com/index.php?_a=product&product_id=41 - also found on eBay)

For logging sound pressure levels (SPL) we have been using a Wensn Digital Sound Level Meter (http://www.wensn.com/html_products/digital-Sound-level-meter-WS1361-17.html - also found on eBay)

With both the temperature and SPL logging, we aren't advertising that the data is 100% accurate as the devices we used are reasonably cheap and therefore aren't perfectly calibrated, however they provide a good way for comparing data over time at a number of locations for a reasonable cost.

When setting up the Raspberry Pi nodes, we found it best to delegate a Pi to either SPL or Temperature monitoring, as we discovered additional modifications needed to be made for logging SPL, broke the temper-poll application used for logging temperature.

Also we found that there was no way to determine the order which USB thermometer would read out the data when using temper-poll, so one reboot would log thermometer A as thermometer A in the database, and then after the next reboot or a USB panic thermometer A would be logging to thermometer B in the database. We recommend only using one USB thermometer per Raspberry Pi for this reason (unless you can work out a way to identify each individual thermometer, as they all seem to have the same HID information because they're the same product).

==============

The folder structure for the scripts are as follows
/temperature is copy of what we have been placing in /opt/temperature for logging temperature.
/spl is a copy of what we have been placing in /opt/spl for logging SPLs.

Information for getting the scripts working can be found in their respective README.txt file.
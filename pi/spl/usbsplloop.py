import sys
import usb.core
import time

# code originally from http://opensource.ebswift.com/RaspiMonitor/wensn/

dev = usb.core.find(idVendor=0x16c0, idProduct=0x5dc)

assert dev is not None

#print dev

#print hex(dev.idVendor) + ', ' + hex(dev.idProduct)


mymin = 0
mymax = 0 
myave = 0 
mytime = time.strftime("%d-%m-%Y_%H:%M")
mycount = 0
mysum = 0
dB = 0

while True:
	ret = dev.ctrl_transfer(0xC0, 4, 0, 0, 200)
	dB = (ret[0] + ((ret[1] & 3) * 256)) * 0.1 + 30

	mycount = mycount + 1
	mysum = mysum + dB
	myave = mysum / mycount
	if dB > mymax:
		mymax = dB
	if mymin == 0:
		mymin = dB
	if mymin > dB:
		mymin = dB

	if mytime != time.strftime("%d-%m-%Y_%H:%M"):
		myresult = str(mytime + " " + str(round(mymax,1)) + " " + str(round(myave,1)) + " " + str(round(mymin,1)))
		f = open('/opt/spl/queue.txt','a')
		f.write(myresult + '\n')
		f.close()

	mymin = 0
	mymax = 0
	myave = 0
	mytime = time.strftime("%d-%m-%Y_%H:%M")
	mycount = 0
	mysum = 0

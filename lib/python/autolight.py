import serial
import sys
import time
import math
import os
import argparse
from crontab import CronTab

# Read arguments
parser = argparse.ArgumentParser()
parser.add_argument("--enable", help="enable automatic light control", action="store_true")
parser.add_argument("--disable", help="disable automatic light control", action="store_true")
parser.add_argument("--status", help="return TRUE or FALSE is the alc enabled", action="store_true")
args = parser.parse_args()

command ='/usr/bin/python /var/www/lib/python/autolight.py'
comment ='Automatic Light Control'
tab = CronTab(user='www-data')

if args.enable:
	cron_job = tab.remove_all(command)
	cron_job = tab.new(command, comment)
	tab.write()
	#print tab.render()
	print comment+" enabled"
	
elif args.disable:
	cron_job = tab.remove_all(command)
	tab.write()
	#print tab.render()
	sys.exit(comment+" jobs cleared.")
	
elif args.status:
	status = "Disabled"
	
	for job in tab.find_command(command):
		if job:
			status = "Enabled"
	print status
	sys.exit()
	
red = 0
green = 0
blue = 0
ww = 0
cw = 0

# Generate time of the date as minutes from midnight. Noon being 720
time = int((float(time.strftime("%H"))*60)+float(time.strftime("%M")))

# Minimum level evening adjustement
if time > 1350:
	time = 1350
	
# Minimum level morning adjustement
if time < 300:
	time = 300
	
offset = 122 #min
length = 1.9


cct = float(time)/229+48.7-(0.0045*(float(offset)-120))
cct = int(math.sin(cct)*1700+3700)

cctdata = os.popen("python /var/www/lib/python/kelvin.py "+str(cct)).read()[0:10]

brightness = (math.exp(-(math.pow((float(time-1440/2-offset)*(0.01)), 2)/(2*(math.pow(length, 2))))))

red = int(int(cctdata[0:2], 16)*brightness)
green = int(int(cctdata[2:4], 16)*brightness)
blue = int(int(cctdata[4:6], 16)*brightness)
ww = int(int(cctdata[6:8], 16)*brightness)
cw = int(int(cctdata[8:10], 16)*brightness)

#print cw

xbee = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=10.0)

from devices import *	# Read device names from another variable

brightness = 15 #int(sigmoidFunction(x))

rgb = '%02X%02X%02X' % (red, green, blue)
wwcw = '%02X%02X%02X' % (ww, cw, 0)

off = "000000"

jalkalamppu1 = '%02X%02X%02X' % (red, green, blue)
jalkalamppu2 = '%02X%02X%02X' % (ww/2, ww/2, 0)

nightstand1 = '%02X%02X%02X' % (red, green, blue)
nightstand2 = '%02X%02X%02X' % (ww, 0, 0)


# Print debug info to console
#print ("Time: %s  RGB: %d %d %d HEX: %s" % (time, red, green, blue, hex))

os.system('python /var/www/lib/python/setPreset.py '+jalkalamppu1+' '+jalkalamppu2+' '+rgb+' '+wwcw+' '+nightstand1+' '+ nightstand2 +' '+rgb+' '+rgb)

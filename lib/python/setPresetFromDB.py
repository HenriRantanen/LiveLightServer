import serial
import sys
import argparse
from database import *
import time
import os
import re # Regular expression

parser = argparse.ArgumentParser()
parser.add_argument("preset", help="ID of the preset", type=str)
parser.add_argument("-c", "--colorcorrection", action="store_true", help="wether to enable color correction")

args = parser.parse_args()

if not args.preset:
    sys.exit("No preset number requested.")

# Read the device ID from the arguments
presetID = str(args.preset)
	
# Query device information from the database
db.execute("SELECT preset_Code, preset_Name FROM table_of_presets WHERE `Preset_ID` = " + presetID)
preset = db.fetchone()

#Break out the data
presetName = preset[1]
presetData = preset[0]

pattern = re.compile("(<\d+>[A-Fa-f0-9]{12}</\d+>)")


# Number of devices in the preset
presetDevices = len(re.findall(pattern, presetData))

# Split single devices into table
presetDataRows = filter(None, re.split(pattern, presetData))

TX = ""

# Disable AutoLight
os.system("sudo /usr/bin/python /var/www/lib/python/autolight.py --disable")

for i in range(len(presetDataRows)):

	# Check if current row makes sense
	m = pattern.match(presetDataRows[i])
	if(m):
		device_ID = filter(None, re.split("</(\d+)>", presetDataRows[i]))[1]
		hex = filter(None, re.split("<\d+>([A-Fa-f0-9]{12})</\d+>", presetDataRows[i]))[0]
		
		# Query device information from the database
		db.execute("SELECT DeviceID, DeviceMAC, DeviceCH1, DeviceCH2, DeviceCH3, DeviceCH4, DeviceCH5, DeviceCH6, DeviceActive FROM table_of_devices WHERE `DeviceID` = "+ device_ID)
		device = db.fetchone()
		
		# Check if device exists
		if device:
			#print device
			if len(device[1])==8:
				devID = device[1]
			else:
				sys.exit("Could not fetch valid device ID")
		else:
			sys.exit("Device not found")
			
		# Check if the device is active
		if not device[8]:
			sys.exit("Device is disabled");
			
		#Check if hex is properly formattted
		if len(hex) == 12 :
			color = hex.upper()
		else:
			sys.exit("HEX-not properly formatted")
			
		# Check wether to do color correction
		if (args.colorcorrection):
			channelMap = []

			# break down the color correction values
			channelMap.append(int(device[2][0:2], 16))
			channelMap.append(int(device[3][2:4], 16))
			channelMap.append(int(device[4][4:6], 16))
			channelMap.append(int(device[5][0:2], 16))
			channelMap.append(int(device[6][2:4], 16))
			channelMap.append(int(device[7][4:6], 16))

			ccolor = ""

			# Do the CC calculations toHEX(color * multiplier)
			for index in range(6):
				ccolor += str("%02X" % ((int(color[index*2:index*2+2], 16)*channelMap[index])/255))

			# Replace the inputeed pattern with the corrected one
			color = ccolor
						
		
		# Create data packet
		TX += "01".decode("hex") 	# Start of header
		TX += devID					# Device ID
		TX += "2"					# command (save color)
		TX += "02".decode("hex") 	# Start of text
		TX += color					# Message
		TX += "03".decode("hex")	# End of text
		TX += "C" 					# SoonToBe Checksum
		TX += "04".decode("hex")	# End of transmission
		
# Make the package	
port = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=3.0)

# Transmit data
port.write(TX)

sys.exit("New preset: " + presetName)
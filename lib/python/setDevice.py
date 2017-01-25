import serial
import sys
import argparse
from database import *
import time
import os

parser = argparse.ArgumentParser()
parser.add_argument("controller", help="DeviceID of the controller", type=str)
parser.add_argument("hex", help="Hexadecimal value to be set", type=str)
parser.add_argument("-c", "--colorcorrection", action="store_true", help="wether to enable color correction")
parser.add_argument("-p", "--packet", action="store_true", help="store the data in a datapacket rather than sending it")

args = parser.parse_args()

if not args.controller:
    sys.exit("No controller set")

# Read the device ID from the arguments
devID = str(args.controller)


# Query device information from the database
db.execute("SELECT DeviceID, DeviceMAC, DeviceCH1, DeviceCH2, DeviceCH3, DeviceCH4, DeviceCH5, DeviceCH6, DeviceActive FROM table_of_devices WHERE `DeviceID` = "+devID)
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

color = "000000000000"

#Check if hex is properly formattted
if len(args.hex) == 12 :
	color = args.hex.upper()
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
TX = "01".decode("hex") 	# Start of header
TX += devID					# Device ID
TX += "2"					# command (save color)
TX += "02".decode("hex") 	# Start of text
TX += color					# Message
TX += "03".decode("hex")	# End of text
TX += "C" 					# SoonToBe Checksum
TX += "04".decode("hex")	# End of transmission

# Output the data
if args.packet:
	# Append data into datapacket file
	with open("/var/www/lib/preset.datapacket", "a") as datapacket:
		datapacket.write(TX)
	datapacket.close()
else:
	# Make the package	
	port = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=3.0)
	
	# Disable AutoLight
	os.system("sudo /usr/bin/python /var/www/lib/python/autolight.py --disable")
	
	# Transmit data
	port.write(TX)
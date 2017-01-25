import serial
import sys

port = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=3.0)

from devices import *	# Read device names from another variable

device = sys.argv[1]
speed = sys.argv[2]

if device == "1":
	dev = dev1
if device == "2":
	dev = dev2
if device == "3":
	dev = dev3
	
#	speed = "0";

TX = "01".decode("hex") 	# Start of header
TX += dev 					# Device ID
TX += "66".decode("hex")
TX += "02".decode("hex") 	# Start of text
TX += speed					# Message
TX += "03".decode("hex")	# End of text
TX += "C" 					# SoonToBe Checksum
TX += "04".decode("hex")	# End of transmission

port.write(TX)
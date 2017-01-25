import serial
import time
import math
import os
import random

#Lopullinen color
pun = 255
vihr = 255
sin = 255

xbee = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=10.0)

from devices import *	# Read device names from another variable

# Run loop 1000 times
for x in range(1, 1000):

	if(random.randint(0,100) > 10):
		brightness = 1
	else:
		brightness = 0
		
	red 	= int(random.randint(0, 255)*brightness)
	green 	= int(random.randint(0, 255)*brightness)
	blue	= int(random.randint(0, 255)*brightness)
	
	# Print debug info to console
	print ("%d:    RGB: %d %d %d" % (x, red, green, blue))

	
	TX3 = "01".decode("hex") 	# Start of header
	TX3 += dev3 					# Device ID
	
	TX3 += "3" 				# Command (Set color, dont save)

	TX3 += "02".decode("hex") 	# Start of text
	TX3 += "{:02X}".format(red)+"{:02X}".format(green)+"{:02X}".format(blue)+"{:02X}".format(red)+"{:02X}".format(green)+"{:02X}".format(blue)	
	TX3 += "03".decode("hex")	# End of text
	TX3 += "CHECKSUM" 			# SoonToBe Checksum
	TX3 += "04".decode("hex")	# End of transmission
	
	xbee.write(TX3)	
	
	time.sleep(random.randint(1, 8))
	

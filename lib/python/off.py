import serial
import sys
import os

#Turn off autolight
os.system("sudo /usr/bin/python /var/www/lib/python/autolight.py --disable")

port = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=3.0)

from devices import *	# Read device names from another variable
	
TX = "01".decode("hex") 	# Start of header
TX += dev1 					# Device ID
TX += "2"
TX += "02".decode("hex") 	# Start of text
TX += "000000000000"		# Message
TX += "03".decode("hex")	# End of text
TX += "C" 			# SoonToBe Checksum
TX += "04".decode("hex")	# End of transmission

TX += "01".decode("hex") 	# Start of header
TX += dev2 					# Device ID
TX += "2"
TX += "02".decode("hex") 	# Start of text
TX += "000000000000"				# Message
TX += "03".decode("hex")	# End of text
TX += "C" 			# SoonToBe Checksum
TX += "04".decode("hex")	# End of transmission

TX += "01".decode("hex") 	# Start of header
TX += dev3 					# Device ID
TX += "2"
TX += "02".decode("hex") 	# Start of text
TX += "000000000000"				# Message
TX += "03".decode("hex")	# End of text
TX += "C" 			# SoonToBe Checksum
TX += "04".decode("hex")	# End of transmission

TX += "01".decode("hex") 	# Start of header
TX += dev4 					# Device ID
TX += "2"
TX += "02".decode("hex") 	# Start of text
TX += "000000000000"				# Message
TX += "03".decode("hex")	# End of text
TX += "C" 			# SoonToBe Checksum
TX += "04".decode("hex")	# End of transmission

port.write(TX)


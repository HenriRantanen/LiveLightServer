import serial
import sys

port = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=3.0)

from devices import *	# Read device names from another variable

colour1 = sys.argv[1]
colour1 += sys.argv[2]
colour2 = sys.argv[3]
colour2 += sys.argv[4]
colour3 = sys.argv[5]
colour3 += sys.argv[6]
colour4 = sys.argv[7]
colour4 += sys.argv[8]

TX = "01".decode("hex") 	# Start of header
TX += dev1 					# Device ID
TX += "2"					# command (save color)
TX += "02".decode("hex") 	# Start of text
TX += colour1					# Message
TX += "03".decode("hex")	# End of text
TX += "C" 					# SoonToBe Checksum
TX += "04".decode("hex")	# End of transmission

TX += "01".decode("hex") 	# Start of header
TX += dev2 					# Device ID
TX += "2"					# command (save color)
TX += "02".decode("hex") 	# Start of text
TX += colour2					# Message
TX += "03".decode("hex")	# End of text
TX += "C" 					# SoonToBe Checksum
TX += "04".decode("hex")	# End of transmission

TX += "01".decode("hex") 	# Start of header
TX += dev3					# Device ID
TX += "2"					# command (save color)
TX += "02".decode("hex") 	# Start of text
TX += colour3					# Message
TX += "03".decode("hex")	# End of text
TX += "C" 					# SoonToBe Checksum
TX += "04".decode("hex")	# End of transmission

TX += "01".decode("hex") 	# Start of header
TX += dev4					# Device ID
TX += "2"					# command (save color)
TX += "02".decode("hex") 	# Start of text
TX += colour4				# Message
TX += "03".decode("hex")	# End of text
TX += "C" 					# SoonToBe Checksum
TX += "04".decode("hex")	# End of transmission

# Append data into datapacket file
#with open("/var/www/lib/preset.datapacket", "a") as datapacket:
#		datapacket.write(TX)

port.write(TX)
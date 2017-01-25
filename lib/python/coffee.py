import serial
import time
import sys

port = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=3.0)

dev1 = "408A9AA4"
dev2 = "409EBBCD"

if sys.argv[1] == "0":
	color1 	= 	"00FF00" #OFF
	color1 +=  	"FF0000"

elif sys.argv[1] == "1":
	color1 	= 	"0000FF" #ON
	color1 +=  	"00FF00"
		
TX = "01".decode("hex") 	# Start of header
TX += dev3 					# Device ID
TX += "3"
TX += "02".decode("hex") 	# Start of text
TX += color1				# Message
TX += "03".decode("hex")	# End of text
TX += "CHECKSUM" 			# SoonToBe Checksum
TX += "04".decode("hex")	# End of transmission

port.write(TX)

time.sleep(2)

color1 	= 	"000000"
color1 +=  	"000000"

TX = "01".decode("hex") 	# Start of header
TX += dev3 					# Device ID
TX += "3"
TX += "02".decode("hex") 	# Start of text
TX += color1				# Message
TX += "03".decode("hex")	# End of text
TX += "CHECKSUM" 			# SoonToBe Checksum
TX += "04".decode("hex")	# End of transmission

port.write(TX)
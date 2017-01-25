import serial
import time
import sys

# set serial port
port = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=1.0)

device = sys.argv[1]

TX = "01".decode("hex") 	# Start of header
TX += device				# Device ID
TX += "s"					# command (transmit signal level)
TX += "02".decode("hex") 	# Start of text
TX += "03".decode("hex")	# End of text
TX += "C" 					# SoonToBe Checksum
TX += "04".decode("hex")	# End of transmission

port.write(TX)

state = port.read()
signal = str(ord(state))

print(signal)

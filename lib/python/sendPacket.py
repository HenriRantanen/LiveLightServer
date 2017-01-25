import serial
import os
import sys

# Port settings
port = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=3.0)

# Packed is stored at
path = "/var/www/lib/preset.datapacket"

# If there is a packet, send it
if os.path.isfile(path):
	# Open the datapacket file
	with open(path, "r") as datapacket:
		TX=datapacket.read()

	# AutoLight Off
	os.system("sudo /usr/bin/python /var/www/lib/python/autolight.py --disable")
	
	# Transmit the datapacket
	port.write(TX)

	# Close and remove the datapacket file
	datapacket.close()
	os.remove(path)
else:
	sys.exit("No datapacket found");
import serial
import os
import sys

# set serial port
port = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=1.0)


# Read server's saved radio ID
path = "/var/www/lib/XBEE.txt"

# If there is a packet, send it
if os.path.isfile(path):
	# Open the datapacket file
	with open(path, "r") as datapacket:
		radioID = datapacket.read()		
		
while 1:
	data = port.readline()
	
	# Recipiant of the datapacket
	recipiant = data[1:9]
	
	# if data is for me
	if recipiant == radioID[8:16]:
		
		# Dig out the command
		command = data[data.index(str(unichr(0x01)))+17:data.index(str(unichr(0x02)))]
		parameter = data[data.index(str(unichr(0x02)))+1:data.index(str(unichr(0x03)))]
		
		# commands to the server
		if command == "4":
						
			# Handle events
			
			if parameter == "0":
				print("kirjaa admin ulos")
				os.system("sudo /usr/bin/python /var/www/lib/python/off.py")
				
				
			if parameter == "1":
				print("kirjaa admin sisaan")
				# AutoLight Off
				os.system("sudo /usr/bin/python /var/www/lib/python/autolight.py --enable")
				

#409EBBCD serverin radio
#testiradio 40C28C68
#01 34 30 39 45 42 42 43 44 34 30 43 32 38 43 36 38 34 02 31 03 43 48 45 43 4B 53 55 4D 04 
#01 34 30 39 45 42 42 43 44 34 30 43 32 38 43 36 38 34 02 31 03 43 48 45 43 4B 53 55 4D 04
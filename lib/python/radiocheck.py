import serial
import time

# set serial port
port = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=1.0)

# send congifuration command
port.write("+++")

# Lue OK (4F 4B 0D)
state = port.readline()
response = 'OK' + str(unichr(0x0D))

# If XBEE responded with OK
if state == response:
	# print ("OK vastattu")
	
	# Ask for the HIGH address
	command = 'ATSH' + str(unichr(0x0D))
	port.write(command)
	ATSH = port.readline()
	
	# Ask for the LOW address
	command = 'ATSL' + str(unichr(0x0D))
	port.write(command)
	ATSL = port.readline()

	address = '00' + ATSH[:-1] + ATSL[:-1]
	
	file = open('/var/www/lib/XBEE.txt','w')
	file.write(address) # python will convert \n to os.linesep
	file.close() # you can omit in most cases as the destructor will call if
	
	# Message shown in startup
	message = "XBEE Radio MAC-Address is "+ address
	print(message)



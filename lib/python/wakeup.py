import serial
import time
import math
import os

#Take autolight off to avoid blinking effects
os.system("/usr/bin/python /var/www/lib/python/autolight.py --disable")

#kalibrointidata
L1b = 0.7 		#lattialampun sininen
L2b = 0.7 		#kattolampun sininen

#Lopullinen color
pun = 255
vihr = 202
sin = 113

# Wake-up parameters
cups = 4 		#cups, How much coffee to be made
fadetime = 30 	#minutes, Duration of the sunrise

# Coffee calculations (valid on Moccamaster)
# How long it takes to make the requested amount of coffee
brewTime = (cups/2)+1.5
# Calculate the time to switch on the coffee maker
coffeeSwitchTime = round((fadetime-brewTime)/fadetime*1000)	

print ("Takes %d min to make the coffee, start at %d" % (brewTime, coffeeSwitchTime))

# S-kurvin funktio
def sigmoidFunction(n):
	return 256*(1/(1 + math.exp((float(-n)/86.65)+5.7)))

# Red kanavan kurvi (Also warm white)
def calcRed(n):
	if n <= 53:
		return n
	elif n <= 96:
		return n+(((n*0.12)-6.36))
	elif n <= 170:
		return n+(((n*-0.26)+29.65))
	else:
		return n+(((n*0.165)-42))

# Green kanavan kurvi	
def calcGreen(n):
	if n <= 53:
		return n
	elif n <= 128:
		return n+(((n*0.12)-6.36))
	elif n <= 192:
		return n+(((n*-0.14)+26.88))
	else:
		return n

# Blue kanavan kurvi (Also pure white)
def calcBlue(n):
	if n <= 4:
		return n+1
	elif n <= 53:
		return n+(n*-0.109)+0.436
	elif n <= 96:
		return (n+(n*-0.23)+12.2)
	elif n <= 170:
		return (n+(n*0.26)-35)
	elif n <= 192:
		return n+9
	else:
		return int(n+(n*-0.14)+35.7)


xbee = serial.Serial("/dev/ttyAMA0", baudrate=9600, timeout=10.0)

from devices import *	# Read device names from another variable

# initialize variable
oldData1 = ""
oldData2 = ""
oldData3 = ""

# Run loop 1000 times
for x in range(1, 1000):
	
	
	# Calculate the S-curve
	brightness = int(sigmoidFunction(x))

	red 	= int(calcRed(brightness)*(float(pun)/255))
	green 	= int(calcGreen(brightness)*(float(vihr)/255))
	blue	= int(calcBlue(brightness)*(float(sin)/255))
	
	# Print debug info to console
	print ("%d:    RGB: %d %d %d" % (x, red, green, blue))

	# Assemble control packets
	# Device 1
	TX1 = "01".decode("hex") 	# Start of header
	TX1 += dev1 					# Device ID
	
	if x == 999:
		TX1 += "2" 					# Command (Set color, save)
	else:
		TX1 += "3" 					# Command (Set color, dont save)
	TX1 += "02".decode("hex") 	# Start of text
	
	TX1 += "{:02X}".format(red)+"{:02X}".format(green)+"{:02X}".format(blue)+"{:02X}".format(red)+"{:02X}".format(green)+"{:02X}".format(int(blue*L1b))
	TX1 += "03".decode("hex")	# End of text
	TX1 += "CHECKSUM" 			# SoonToBe Checksum
	TX1 += "04".decode("hex")	# End of transmission

	# Device 2
	TX2 = "01".decode("hex") 	# Start of header
	TX2 += dev2 					# Device ID
	
	if x == 999:
		TX2 += "2" 				# Command (Set color, save)
	else:
		TX2 += "3" 				# Command (Set color, dont save)
	TX2 += "02".decode("hex") 	# Start of text

	TX2 += "{:02X}".format(red)+"{:02X}".format(green)+"{:02X}".format(blue)+"{:02X}".format(red)+"{:02X}".format(int(blue*L2b))+"{:02X}".format(0)	
	TX2 += "03".decode("hex")	# End of text
	TX2 += "CHECKSUM" 			# SoonToBe Checksum
	TX2 += "04".decode("hex")	# End of transmission

	# Device 3
	TX3 = "01".decode("hex") 	# Start of header
	TX3 += dev3 					# Device ID
	
	if x == 999:
		TX3 += "2" 				# Command (Set color, save)
	else:
		TX3 += "3" 				# Command (Set color, dont save)

	TX3 += "02".decode("hex") 	# Start of text
	TX3 += "{:02X}".format(red)+"{:02X}".format(green)+"{:02X}".format(blue)+"{:02X}".format(red)+"{:02X}".format(green)+"{:02X}".format(int(blue*L1b))
	TX3 += "03".decode("hex")	# End of text
	TX3 += "CHECKSUM" 			# SoonToBe Checksum
	TX3 += "04".decode("hex")	# End of transmission
	
	
	# Do not resend the same data, only if changed
	if oldData1 != TX1:
		xbee.write(TX1)	
		oldData1 = TX1
	# Wake up cycle 45min * 60 /1000	
	time.sleep(0.6)
	if oldData2 != TX2:
		xbee.write(TX2)	
		oldData2 = TX2
	time.sleep(0.6)
	#time.sleep(fadetime*60/1000)
	
	if oldData3 != TX3:
		xbee.write(TX3)	
		oldData3 = TX3
	time.sleep(0.6)	
	# Swich on the coffeemaker if its time
	if x == coffeeSwitchTime:
		os.system("python /var/www/python/coffee.py 1")
	
	if x == 999:
		os.system("/usr/bin/python /var/www/lib/python/autolight.py --enable")
	
	
	#time.sleep(0.125)
	

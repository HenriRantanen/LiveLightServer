import serial
import sys
import time
import math
import os
import argparse

# Read arguments
parser = argparse.ArgumentParser()
parser.add_argument("kelvin", help="Hexadecimal value to be set", type=str)
args = parser.parse_args()

cct = int(args.kelvin)
#red = 255
#green = int((math.log10((cct-1000)/266)*(152.6))+(cct*5.8/1000))
#blue = int(cct/23.2-67.9)

if cct < 6500:
	red = 255
	green = math.log10((cct-1000)/(332.9))*(209.4)
	blue = (0.0531*(cct-2000))+16
elif cct == 6500:
	red = green = blue = 255
else:
	red = -math.log10(float(cct-6000)/float(100000000))*70.8-120.5
	green = -math.log10(float(cct-6000)/float(100000000))*(43-0.74)+31
	blue = 255
	
if cct < 2450:
	ww = 0
	cw = 0
elif cct < 3500:
	ww = 255
	cw = math.pow((cct/(84.1)-25.2), 2)-15.5
elif cct == 3500:
	ww = 255
	cw = 255
elif cct <= 5540:
	ww = math.pow((cct/(158.6)-38.4),2)-12
	cw = 255
else:
	ww = 0
	cw = 0
	
	
color = "{:02X}".format(int(red))+"{:02X}".format(int(green))+"{:02X}".format(int(blue))

color += "{:02X}".format(int(ww))+"{:02X}".format(int(cw))

print color
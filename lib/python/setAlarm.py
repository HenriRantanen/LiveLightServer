import sys
import time
import os
import argparse
from crontab import CronTab

parser = argparse.ArgumentParser()
parser.add_argument("-hr", "--hours", type=int, help="0-23", default=0)
parser.add_argument("-min", "--minutes", type=int, help="0-59", default=0)
parser.add_argument("--remove", help="remove the wakeup", action="store_true")
parser.add_argument("--time", help="remove the wakeup", action="store_true")
args = parser.parse_args()

# Read arguments
hour = args.hours
minute = args.minutes

command ='/usr/bin/python /var/www/lib/python/wakeup.py'
comment ='Wakeup'
tab = CronTab(user='www-data')

if args.remove:
	cron_job = tab.remove_all(command)
	tab.write()
	sys.exit(comment+" jobs cleared.")
	
elif args.time:
	hour = minute = "--"
	for job in tab.find_command(command):
		hour = "%02d" % int(str(job.hour))
		minute = "%02d" % int(str(job.minute))
	
	print hour+":"+minute
	sys.exit()
	
else:
	cron_job = tab.remove_all(command)
	cron_job = tab.new(command, comment)

	cron_job.hour.on(hour)
	cron_job.minute.on(minute)

	# Write the wakeup
	tab.write()
	print "Alarm set to "+str("%02d" % hour)+":"+str("%02d" % minute)
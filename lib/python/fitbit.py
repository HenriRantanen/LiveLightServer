import urllib2
import time
import json
from pprint import pprint

key = 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI0WVFUMjMiLCJhdWQiOiIyMjdYQlkiLCJpc3MiOiJGaXRiaXQiLCJ0eXAiOiJhY2Nlc3NfdG9rZW4iLCJzY29wZXMiOiJyc29jIHJzZXQgcmFjdCBybG9jIHJ3ZWkgcmhyIHJudXQgcnBybyByc2xlIiwiZXhwIjoxNTEwNTg5MzM3LCJpYXQiOjE0NzkwNTMzMzd9.F-4_7lx71FJUdzLYdY23wDjtNwGdr8YlFotMqtxHLfc'
today = time.strftime("%Y-%m-%d")

def getJson( url ):
	req = urllib2.Request(url)
   	req.add_header('Authorization', key)
	resp = urllib2.urlopen(req)
	content = resp.read()
	data = json.loads(content)
   	return data

foodApiUrl = 'https://api.fitbit.com/1/user/-/foods/log/date/'+today+'.json'
foodJson = getJson(foodApiUrl)

weightGoalApiUrl = 'https://api.fitbit.com/1/user/-/body/log/weight/goal.json'
weightGoalJson = getJson(weightGoalApiUrl)

weightApiUrl = 'https://api.fitbit.com/1/user/-/body/log/weight/date/'+today+'.json'
weightJson = getJson(weightApiUrl)

fatGoalApiUrl = 'https://api.fitbit.com/1/user/-/body/log/fat/goal.json'
fatGoalJson = getJson(fatGoalApiUrl)

print str(foodJson["goals"]["estimatedCaloriesOut"])+', '+str(foodJson["goals"]["calories"])+', '+str(foodJson["summary"]["calories"])+', '+str(foodJson["summary"]["water"])+', '+str(weightGoalJson["goal"]["startDate"])+', '+str(weightGoalJson["goal"]["startWeight"])+', '+str(weightGoalJson["goal"]["weight"])+', '+str(fatGoalJson["goal"]["fat"])+', '+str(weightJson["weight"][0]["weight"])+', '+str(weightJson["weight"][0]["fat"])

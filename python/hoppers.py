import requests

access_token = '[ACCESS TOKEN]'
uri = 'https://api.cryptohopper.com/v1/hopper'
headers = {
	'access-token': access_token
}

response = requests.get(uri, headers=headers)

# The response will be all of your hoppers
print(response.json())

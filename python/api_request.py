import requests

access_token = '[ACCESS TOKEN]'
base_url = 'https://api.cryptohopper.com/v1/'
endpoint = 'hopper'
uri = base_url + endpoint
headers = {
	'access-token': access_token
}

response = requests.get(uri, headers=headers)

# The response will be all of your hoppers
print(response.json())

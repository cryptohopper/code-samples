import requests

client_id = '[CLIENT ID]'
client_secret = '[CLIENT SECRET]'
redirect_uri = 'http://localhost/'

# Use the same scope as the one in the Cryptohopper app console
scope = 'read,notifications,manage,trade'

authorize_url = 'https://www.cryptohopper.com/oauth2/authorize'
code_uri = authorize_url + '?client_id=' + client_id + '&response_type=code&scope=' + scope + '&state=any&redirect_uri=' + redirect_uri

# The user logs in, accepts your client authentication request
requests.get(code_uri)

# The user will be redirected to the redirect_uri with the code as GET parameter
# ex: http://localhost/auth?code=123456789
code = '123456789' # Put the code you got here

token_url = 'https://www.cryptohopper.com/oauth2/token'

#Grab that code and exchange it for an `access_token`
access_token = requests.post(
    token_url,
    data={
        'grant_type': 'authorization_code',
        'code': code,
        'client_id': client_id,
        'client_secret': client_secret,
        'redirect_uri': redirect_uri
    }
)

print(access_token.json())
# Response:
# {
# 'access_token': '[ACCESS TOKEN]', 
# 'expires_in': 31556952, 
# 'token_type': 'Bearer', 
# 'scope': 'read,notifications,manage,trade', 
# 'refresh_token': '[REFRESH TOKEN]'
# }
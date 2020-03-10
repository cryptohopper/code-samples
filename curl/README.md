# Curl implementation
This Curl implementation can be translated to use for every kind of programming language that can do http requests. 
<br /><br />
Steps:
1. Authorize with [Authorization Code Grant](#authorization-code-grant). You'll be redirected to Cryptohopper.com if you are not logged in. Login and try again.
2. Save the token received in the last step.
3. Get an access token with [Requesting an access token](#requesting-an-access-token).
4. Now you can use the access token to do requests.
<br />

## Authorization Code Grant

Go to and login with Cryptohopper:
```
https://www.cryptohopper.com/oauth2/authorize?response_type=code&client_id=[CLIENT ID]&state=any&scope=[SCOPE]&redirect_uri=[REDIRECT URI]
```
<br />
Parameters: <br />
<strong>response_type</strong> <i>(required)</i> value must be: code <br />
<strong>client_id</strong> <i>(required)</i> can be found in the Cryptohopper app console <br />
<strong>state</strong> <i>(required)</i> value doesn't matter, we use: any <br />
<strong>scope</strong> <i>(required)</i> can be found in the Cryptohopper app console (example: read,notifications,manage,trade) <br />
<strong>redirect_uri</strong> <i>(required)</i> example: http://localhost/ <br />
<br />

Response:<br />
[REDIRECT URI]?code=[CODE]&state=[STATE]
<br />

Example response:
```
HTTP/1.1 302 Found
Location: http://localhost/?code=123456789&state=any
```
<br />

## Requesting an access token 
```
$ curl -X POST -H "Content-Type: application/x-www-form-urlencoded" -d "grant_type=authorization_code&code=[CODE]&redirect_uri=[REDIRECT URI]&client_id=[CLIENT ID]&client_secret=[CLIENT SECRET]" https://www.cryptohopper.com/oauth2/token
```
<br />
Parameters: <br />
<strong>grant_type</strong> <i>(required)</i> value must be: authorization_code <br />
<strong>code</strong> <i>(required)</i> received in the last step <br />
<strong>redirect_uri</strong> <i>(required)</i> example: http://localhost/ <br />
<strong>client_id</strong> <i>(required)</i> can be found in the Cryptohopper app console <br />
<strong>client_secret</strong> <i>(required)</i> can be found in the Cryptohopper app console <br />
<br />

Example response:
```
{
"access_token": [ACCESS TOKEN],
"expires_in": 31556952,
"token_type": "Bearer",
"scope": [SCOPE], 
"refresh_token": [REFRESH TOKEN]
}
```
<br />

## Using the access token
This is an example for making a request to the Cryptohopper API. The <i>/hopper</i> endpoint can be replaced with other endpoints. To view all of the endpoints go to: https://api.cryptohopper.com/v1. 
```
$ curl -i -H "access-token: [ACCESS TOKEN]" https://api.cryptohopper.com/v1/hopper
```
<br />
Parameters: <br />
<strong>access-token</strong> <i>(required)</i> <br />
<br />

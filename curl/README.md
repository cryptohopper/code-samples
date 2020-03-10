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
**response_type** _(required)_ value must be: code <br />
**client_id** _(required)_ can be found in the Cryptohopper app console <br />
**state** _(required)_ value doesn't matter, we use: any <br />
**scope** _(required)_ can be found in the Cryptohopper app console (example: read,notifications,manage,trade) <br />
**redirect_uri** _(required)_ example: http://localhost/ <br />
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
**grant_type** _(required)_ value must be: authorization_code <br />
**code** _(required)_ received in the last step <br />
**redirect_uri** _(required)_ example: http://localhost/ <br />
**client_id** _(required)_ can be found in the Cryptohopper app console <br />
**client_secret** _(required)_ can be found in the Cryptohopper app console <br />
<br />

Example response:
```json
{
"access_token": [ACCESS TOKEN],
"expires_in": 31556952,
"token_type": "Bearer",
"scope": [SCOPE], 
"refresh_token": [REFRESH TOKEN]
}
```
<br />
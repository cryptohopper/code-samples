# Node.js integration

The provided `app.js` example app shows how to use the `simple-oauth2` package to obtain an OAuth2 access token from Cryptohopper.com

## How to run the example

Requirements: 

* Node.js >= 12.x
* simple-oauth2 v4

```
npm install
node app.js
```

The main endpoint `http://localhost:3000/` is a protected resource. 

That means it shows an unauthorized error message in case the user is not (yet) logged in. 

The `http://localhost:3000/auth` endpoint calls the `/oauth2/authorize` endpoint at Cryptohopper.com. 

The client is then redirected to a cryptohopper.com page to login and granth authorization (based on the needed scopes).

Next, the client is redirected back to the app configured redirect URI (`http://localhost:3000/callback`), along with a authorization code parameter. 

Finally, the auth code is used when calling `/oauth2/token` to obtain an access token. 
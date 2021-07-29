const express = require('express');
const path = require('path');
const axios = require('axios');

const { ClientCredentials, ResourceOwnerPassword, AuthorizationCode } = require('simple-oauth2');

const app = express();
const port = process.env.PORT || 3000;

const cryptohopperURL = 'https://www.cryptohopper.com';
const clientID = '';
const clientSecret = '';
const redirectURI = 'http://localhost:3000/callback';

const client = new AuthorizationCode({
    client: {
        id: clientID,
        secret: clientSecret
    },
    auth: {
        tokenHost: cryptohopperURL,
        authorizeHost: cryptohopperURL,
        tokenPath: '/oauth2/token',
        authorizePath: '/oauth2/authorize'
    }
});

var token = {};

/* 
    '/' is a protected resource:
    
    - if the access token is not set (yet) then access is forbidden
    - if the access token is set then use it to call an API endpoint
*/
app.get('/', (req, res) => {
    if (!token.access_token) {
        res.send('Unauthorized. Please visit http://localhost:' + port + '/auth to login')

        return
    }

    axios.get('https://api.cryptohopper.com/v1/hopper', { headers: { 'access-token': token.access_token } })
        .then(response => {
            console.log(response);

            res.send(JSON.stringify(response.data));
        })
        .catch(error => {
            console.log(error);

            res.send(error);
        });    
});

/*
    '/auth' redirects the client to cryptohopper.com for the user to grant authorization to the app.

    In case of success, the client is redirected back to the specified URI '/callback'.
*/
app.get('/auth', (req, res) => {
    const authorizationUri = client.authorizeURL({
        redirect_uri: redirectURI,
        scope: 'read',
        state: 'any'
    });

    res.redirect(authorizationUri);
});

/*
    '/callback' expects a `code` query parameter, from the OAuth redirect. 

    The `code` is then used to retrieve the actual OAuth access_token.
*/
app.get('/callback', async (req, res, next) => {
    if (!req.query.code) {
        res.send('missing authorization code.')

        return
    }

    try {
        response = await client.getToken({
            code: req.query.code,
            redirect_uri: redirectURI
        });

        // to refresh the token:
        //
        // let accessToken = client.createToken(response.token);
        // accessToken = await accessToken.refresh({scope: 'read'});

        token = response.token;

        res.redirect('/');
    } catch (err) {
        console.log('access token error', err.message)
        next(err);
    }
});

app.listen(port);
console.log('Server started at http://localhost:' + port);
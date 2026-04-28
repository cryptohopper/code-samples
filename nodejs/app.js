// Cryptohopper OAuth2 sample — Node.js 18+, no third-party HTTP client.
//
// Demonstrates the three-leg OAuth2 authorization-code flow against
// cryptohopper.com and one authenticated call against api.cryptohopper.com.
//
// Set CRYPTOHOPPER_CLIENT_ID and CRYPTOHOPPER_CLIENT_SECRET in your env
// (issue these from https://www.cryptohopper.com developer dashboard).

const express = require('express');
const crypto = require('crypto');

const app = express();
const port = process.env.PORT || 3000;

const CRYPTOHOPPER_HOST = 'https://www.cryptohopper.com';
const API_HOST = 'https://api.cryptohopper.com';

const clientID = process.env.CRYPTOHOPPER_CLIENT_ID || '';
const clientSecret = process.env.CRYPTOHOPPER_CLIENT_SECRET || '';
const redirectURI = `http://localhost:${port}/callback`;

if (!clientID || !clientSecret) {
    console.warn(
        '⚠ Set CRYPTOHOPPER_CLIENT_ID and CRYPTOHOPPER_CLIENT_SECRET in your\n' +
        '   environment before starting this sample. The /auth flow will fail\n' +
        '   without them.'
    );
}

let token = null;

// '/' is the protected resource. It calls one API endpoint with the access
// token to demonstrate that the token works.
app.get('/', async (req, res, next) => {
    if (!token?.access_token) {
        return res.send(
            `Unauthorized. Visit <a href="/auth">/auth</a> to log in.`
        );
    }

    try {
        // The Cryptohopper public API uses the `access-token` HTTP header,
        // NOT the OAuth2-conventional `Authorization: Bearer`. The AWS API
        // Gateway in front of the production API rejects Bearer with a
        // SigV4 parser error. See:
        //   https://www.cryptohopper.com/api-documentation/how-the-api-works
        const r = await fetch(`${API_HOST}/v1/hopper`, {
            headers: { 'access-token': token.access_token },
        });
        const data = await r.json();
        res.json(data);
    } catch (err) {
        next(err);
    }
});

// '/auth' redirects the browser to cryptohopper.com for the user to grant
// authorization to this app.
app.get('/auth', (req, res) => {
    const params = new URLSearchParams({
        client_id: clientID,
        redirect_uri: redirectURI,
        response_type: 'code',
        scope: 'read',
        // Random CSRF state. In production you'd persist this per-session
        // and verify it on the callback.
        state: crypto.randomBytes(16).toString('hex'),
    });
    res.redirect(`${CRYPTOHOPPER_HOST}/oauth2/authorize?${params}`);
});

// '/callback' receives the OAuth `code` and exchanges it for an access token.
app.get('/callback', async (req, res, next) => {
    if (!req.query.code) {
        return res.status(400).send('missing authorization code.');
    }

    try {
        const body = new URLSearchParams({
            grant_type: 'authorization_code',
            client_id: clientID,
            client_secret: clientSecret,
            redirect_uri: redirectURI,
            code: req.query.code,
        });

        const r = await fetch(`${CRYPTOHOPPER_HOST}/oauth2/token`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body.toString(),
        });

        if (!r.ok) {
            const text = await r.text();
            return res.status(502).send(`token exchange failed (${r.status}): ${text}`);
        }

        token = await r.json();
        res.redirect('/');
    } catch (err) {
        next(err);
    }
});

app.listen(port, () => {
    console.log(`Server started at http://localhost:${port}`);
});

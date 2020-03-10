// npm install --save simple-oauth2

const credentials = {
  client: {
    id: '[CLIENT ID]',
    secret: '[CLIENT SECRET]'
  },
  auth: {
    tokenHost: 'https://www.cryptohopper.com/oauth2/authorize'
  }
};

// Use the same scope as the one in the Cryptohopper app console
const scope = 'read,notifications,manage,trade';
 
const oauth2 = require('simple-oauth2').create(credentials);

async function run() {
  const oauth2 = require('simple-oauth2').create(credentials);
 
  const authorizationUri = oauth2.authorizationCode.authorizeURL({
    redirect_uri: 'http://localhost:3000/callback',
    scope: scope,
    state: 'any'
  });
 
  // The user will be redirected to the redirect_uri with the code as GET parameter using Express
  // ex: http://localhost/auth?code=123456789
  res.redirect(authorizationUri);
  let code = '123456789' # Put the code you got here
 
  const tokenConfig = {
    code: code,
    redirect_uri: 'http://localhost:3000/callback',
    scope: scope,
  };
 
  try {
    const result = await oauth2.authorizationCode.getToken(tokenConfig);
    const accessToken = oauth2.accessToken.create(result);
  } catch (error) {
    console.log('Access Token Error', error.message);
  }
}
 
run();
async function run() {
  // Use the same scope as the one in the Cryptohopper app console
  const scope = 'read,notifications,manage,trade';

  const tokenObject = {
    'access_token': '[ACCESS TOKEN]',
    'refresh_token': '[REFRESH TOKEN]',
    'expires_in': '7200'
  };
 
  let accessToken = oauth2.accessToken.create(tokenObject);
 
  if (accessToken.expired()) {
    try {
      const params = {
        scope: scope,
      };
 
      accessToken = await accessToken.refresh(params);
    } catch (error) {
      console.log('Error refreshing access token: ', error.message);
    }
  }
}
 
run();
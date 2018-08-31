// Create a new Hopper trading bot
$(function() {
  
  $.ajax({
        url      : "https://api.cryptohopper.com/v1/hopper",
        type     : "POST",
        headers  : { 
						"Content-Type:"     : "application/json; charset=UTF-8",
						"x-api-access-key:" : "YOUR_ACCESS_TOKEN"  
				   },
	    dataType : 'json',
		data     : {
						"name"            : "My First Hopper",   // Your Hopper Name
						"exchange"        : "poloniex",          // Your exchange
						"buying_enabled"  : 0,                   // Disable your Hopper from buying
						"selling_enabled" : 0,                   // Disable your Hopper from selling
						"api_config"      : {                                                        // Your API configuration
												"api_key"    : "YOUR_EXCHANGE_API_KEY",
												"api_secret" : "YOUR_EXCHANGE_API_SECRET"
											}
					},
        success  : function() { 
					  alert('Success!' + authHeader); 
				   }
  });
  
});

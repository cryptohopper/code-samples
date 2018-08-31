<?php

function makeAPICall($URL, $method, $data, $API_key)
{
    $ch = curl_init($URL);
    
    switch ($method) {
        case 'GET'    : curl_setopt($ch, CURLOPT_POST, 0); break;
        
        case 'POST'   : curl_setopt($ch, CURLOPT_POST, 1);
        
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        break;
        
        case 'PATCH'  : curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        break;
        
        case 'DELETE' : curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); break;
    }
    
    // Set cURL options
    $arrHeaders = ['x-api-access-key: ' . $API_key,
                   'Content-Type: application/json; charset=UTF-8',
                  ];
    
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_HTTPHEADER,     $arrHeaders);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH,       CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    
    if (!$response = curl_exec($ch)) {
        return false;
    }
    
    $server_info = curl_getinfo($ch);
    curl_close($ch);
    
    return array('response'    => json_decode($response, true),
                 'server_info' => $server_info,
                );
}


// API keys
$URL           = 'https://api.cryptohopper.com/v1/';
$API_key       = '';


// Make GET request
$endpoint = $URL . 'hopper';
$data     = '';

$result = makeAPICall($endpoint, 'GET', $data, $API_key);

var_dump($result['response']);


// Make POST request
$data     = array('name'     => 'My First Hopper', 
                  'exchange' => 'poloniex',
                  'enabled'  => 1,
                  'api_key'  => array('api_key'    => 'MY KEY',
                                      'api_secret' => 'MY SECRET',
                                     ),
                 );

$result = makeAPICall($endpoint, 'POST', $data, $API_key);


var_dump($result['response']);


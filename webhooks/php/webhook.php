<?php

$app_secret = 'ENTER_YOUR_APP_SECRET';
$verification_code = 'MY_VERIFICATION_CODE';

$requestBody = file_get_contents('php://input');
$headers = getallheaders();
$headers = array_change_key_case($headers, CASE_UPPER);
$json = json_decode($requestBody, true);

$signature = hash_hmac('sha512', $requestBody, $app_secret);

if($signature == $headers['X-HUB-SIGNATURE']){
    //valid webhook request
    if($json['type'] == 'validate'){
        // validate webhook
        echo $verification_code;
    }elseif($json['type'] == 'messages'){
        // receive messages
        print_r($json['messages'], true);
    }else{
        // unkown type
        echo 'Unkown webhook message type.';
    }
}else{
    //invalid signature
    echo 'Invalid signature.';
}
?>
<?php
$access_token = 'ENTER_YOUR_ACCESS_TOKEN';
$operation = 'hopper';
$method = 'GET';
$data_string = '{}';

$path = '/v1/'.$operation;

$headers = array(
    'access_token: '.$access_token
);

$ch = curl_init($api_url.$path);

if($method == 'POST'){
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Content-Length: ' . strlen($data_string);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
}elseif($method == 'GET'){
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

}elseif($method == 'PATCH'){
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Content-Length: ' . strlen($data_string);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
}elseif($method == 'DELETE'){
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'Content-Length: ' . strlen($data_string);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
}

$result = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

$result_json = json_decode($result, true);
if(is_array($result_json)){
    $result_json = json_encode($result_json, JSON_PRETTY_PRINT);
}

echo '<h3>Endpoint: <strong>'.$operation.'</strong></h3>';
echo '<h3>Method: <strong>'.$method.'</strong></h3>';
echo '<h3>Status code: <strong>'.$httpcode.'</strong></h3><br><hr>';
echo '<h3>Result</h3><br><br><pre>';
if(!empty($result_json)){
    echo $result_json;
}else{
    echo $result;
}
echo '</pre><br><br>';
if(!empty($error)){
    echo '<h3>Error</h3><br><br>';
    echo $error;
}
curl_close($ch);
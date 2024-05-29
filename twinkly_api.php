<?php
header('Content-Type: application/json');

$address = $_GET['address'];
$command = $_GET['command'];

function twinkly($address, $command = 0){
    $auth = twinklycnt($address, 1);
    $token = $auth['authentication_token'];
    twinklycnt($address, 2, $token, $auth['challenge-response']);
    if($command > 1) $res = twinklycnt($address, 4, $token, $command);
    else $res = twinklycnt($address, 3, $token, $command);
    return $res;
}

function twinklycnt($address, $step, $token = '', $data = ''){
    if($step == 1) {
        $path = "/xled/v1/login";
        $challenge = base64_encode(random_bytes(32));
        $post = '{"challenge":"'.$challenge.'"}';
    }else if($step == 2){
        $path = "/xled/v1/verify";
        $post = '{"challenge-response":"'.$data.'"}';
    }else if($step == 3){
        $path = "/xled/v1/led/mode";
        if($data == 1) $post = '{"mode":"color"}';
        else $post = '{"mode":"off"}';
    }else if($step == 4){
        $path = "/xled/v1/led/out/brightness";
        $post = '{"mode":"enabled","value":'.$data.'}';
    }
    $ch = curl_init('http://'.$address.$path);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, Array('Content-Type: application/json;charset=UTF-8', 'X-Auth-Token: '.$token));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $html = curl_exec($ch);
    curl_close($ch);
    return(json_decode($html, true));
}

echo json_encode(twinkly($address, $command));

<?php

require_once '../../wp-config.php';

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.sbis.ru/retail/img?' . http_build_query($_GET),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => [
        'X-SBISAccessToken: ' . SBIS_TOKEN
    ],
));

$response = curl_exec($curl);

curl_close($curl);

header('Content-type: image/jpeg');
echo $response;

<?php

require_once __DIR__ . '/vendor/autoload.php';

$username = 'masteruan';

$url = 'https://europe-west1-goreply-a2a-lora-hackathon.cloudfunctions.net/bq2jsonConnector';


function hexToStr($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}

function getData(array $data)
{
    if(empty($data))
        return null;

    $string = hexToStr($data[0]['values'][0]['value']['payload']);
    $values = explode(',', $string);

    $values = array_filter($values);

    $mappedValues = [
        'field1' => $values[0],
        'field2' => $values[1],
        'field3' => $values[2],
        'field4' => $values[3],
        'field5' => $values[4],
    ];

    print_r($mappedValues);

    return $mappedValues;
}

while(true) {
    $startDate = ((time() - 1) * 1000) - 5;
    $endDate = time() * 1000;

    $payload = [
        'username' => $username,
        'startDate' => $startDate,
        'endDate' => $endDate,
    ];

    $client = new \GuzzleHttp\Client([
        'http_errors' => false
    ]);

    $res = $client->post($url, [
        \GuzzleHttp\RequestOptions::JSON => $payload,
    ]);

    $body = $res->getBody();

    $data = json_decode($body, true);

    $data = getData($data);

    if(null === $data) {
        echo "No data :(\n";

        continue;
    }

    $client->get('https://api.thingspeak.com/update?api_key=Z3ONEM1MCZLNLBSP&' . http_build_query($data));

    sleep(1);
}

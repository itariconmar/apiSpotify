<?php

require 'vendor/autoload.php';

// Create and configure Slim app
$config = ['settings' => [
    'addContentLengthHeader' => false,
]];
$app = new \Slim\App($config);

// Define app routes
$app->get('/hello/{name}', function ($request, $response, $args) {
    return $response->write("Hello " . $args['name']);
});

$app->get('/v1/albums', function ($request, $response, $args) {

    $banda = $request->getQueryParam('q');

    if(!$banda){
        $answer = [
            'error' => true,
            'description' => 'No hay parametro de busqueda'
        ];

        return $response->write($answer);
    }

    $banda = urlencode($banda);



    // LLamado del Token
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://accounts.spotify.com/api/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Basic YWQ0MzNiNzJmMzZjNDdlMzgxYmUwNjNlMDcyZjI5MTM6NWYyODY4ZTQ1NzdiNDVmODljMDY5NGJkMjIwOWYxNTY=',
            'Content-Type: application/x-www-form-urlencoded',
            'Cookie: __Host-device_id=AQDwll9KIM3XJl8vt2oNX0p-feK9-IJb364gkp36feB9_oLOzrZXTVzb98Qjmg8Y32A0nYH3IrzMROTMSTjqenoa2DjVGDg4s-E; sp_tr=false'
        ),
    ));

    $responseToken = curl_exec($curl);
    curl_close($curl);


    $arrayToken = json_decode($responseToken, true);
    $token = $arrayToken["access_token"];

    if(!$token){
        $answer = [
            'error' => true,
            'description' => 'Error obteniendo el token'
        ];

        return $response->write($answer);
    }

    // Busqueda del album

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.spotify.com/v1/search?q=' . $banda .'&type=album&limit=10',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '. $token
        ),
    ));

    $responseAlbums = curl_exec($curl);

    curl_close($curl);

    $arrayAlbums = json_decode($responseAlbums, true);


    $totalResponse = [];

    foreach ($arrayAlbums['albums']['items'] as $album){

        $albumResponse = [
            'name' => $album['name'],
            'released' => $album['release_date'],
            'tracks' => $album['total_tracks'],
            'cover' => [
                'height' => $album['images'][0]['height'],
                'width' => $album['images'][0]['width'],
                'url' => $album['images'][0]['url']
            ]

        ];

        array_push($totalResponse, $albumResponse);

    }

    $totalResponse = json_encode($totalResponse);

    return $response->write($totalResponse);

});

// Run app
$app->run();

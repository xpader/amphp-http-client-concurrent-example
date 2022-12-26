<?php

use Amp\Http\Client\HttpClientBuilder;
use Amp\Http\Client\Request;
use Amp\Pipeline\Pipeline;

require __DIR__.'/vendor/autoload.php';

$pipeline = Pipeline::fromIterable(function (): \Generator {
    for ($i = 0; $i < 100; ++$i) {
        yield 'https://api.wrdan.com/ip';
    }
});

$httpClient = HttpClientBuilder::buildDefault();

$pipeline
    ->concurrent(5)
    ->forEach(function($url) use ($httpClient) {
        $request = new Request($url);
        $response = $httpClient->request($request);
        echo $response->getBody()->buffer();
    }
);

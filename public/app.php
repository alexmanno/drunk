<?php

require_once __DIR__ . '/../src/Core/autoload.php';

(function () {
    $kernel = new \AlexManno\Drunk\Core\Kernel();
    $kernel->boot();

    $request = \Zend\Diactoros\ServerRequestFactory::fromGlobals();

    $response = $kernel->handle($request);

    $emitter = new \Zend\HttpHandlerRunner\Emitter\SapiEmitter();

    return $emitter->emit($response);
})();
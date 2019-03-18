<?php

use AlexManno\Drunk\Core\Kernel;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\EmitterInterface;

require_once __DIR__ . '/../src/Core/autoload.php';

(function () {
    $kernel = Kernel::create()->boot();

    $request = ServerRequestFactory::fromGlobals();

    $response = $kernel->handle($request);

    return $kernel->getContainer()->get(EmitterInterface::class)->emit($response);
})();
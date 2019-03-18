<?php

use AlexManno\Drunk\Core\Kernel;
use AlexManno\Drunk\Core\Services\RequestHandler;
use Zend\Diactoros\ServerRequestFactory;
use Zend\HttpHandlerRunner\Emitter\EmitterInterface;

require_once __DIR__ . '/../src/Core/autoload.php';

(function () {
    $container = Kernel::create()->boot()->getContainer();

    $request = ServerRequestFactory::fromGlobals();

    $response = $container->get(RequestHandler::class)->handle($request);

    return $container->get(EmitterInterface::class)->emit($response);
})();
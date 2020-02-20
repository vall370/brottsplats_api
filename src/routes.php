<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
    use \Tuupola\Middleware\Cors;
$options = [
"origin" => "*",
"methods" => ["GET", "POST", "PUT", "PATCH", "DELETE"],
"headers.allow" => [],
"headers.expose" => [],
"credentials" => false,
"cache" => 0,
"error" => null
];
$app->add(new Tuupola\Middleware\Cors($options));
});

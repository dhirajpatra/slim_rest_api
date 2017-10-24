<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

require_once('TokenAuth.php');
$container = $app->getContainer();

$app->add(new \TokenAuth($container));

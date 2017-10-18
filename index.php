<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/settings.php';
$app = new \Slim\App($settings);


// Fetch DI Container
$container = $app->getContainer();

// PDO object to work on
$container['PDO'] = function ($config) {
    $setting = $config['settings']['db'];
    return new \Slim\PDO\Database($setting['dsn'], $setting['usr'], $setting['pwd']);
};

// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(__DIR__ . '/public/templates/', [
        //'cache' => 'var/cache/twig',
        'cache' => false,
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

// Set up dependencies
require __DIR__ . '/src/dependencies.php';

// Register middleware
require __DIR__ . '/src/middleware.php';

// Register routes
require __DIR__ . '/src/routes.php';

$models = glob(__DIR__ . '/src/models/*.php');
foreach ($models as $model) {
    require $model;
}

// Run app
$app->run();

<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// PDO object to work on
$container['PDO1'] = function ($c) {
    $setting = $c->get('settings')['db']['pdo1'];
    return new \Slim\PDO\Database($setting['dsn'], $setting['usr'], $setting['pwd']);
};

$container['PDO2'] = function ($c) {
    $setting = $c->get('settings')['db']['pdo2'];
    return new \Slim\PDO\Database($setting['dsn'], $setting['usr'], $setting['pwd']);
};

// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../public/templates/', [
        'cache' => __DIR__ . '/../var/cache/twig',
        'cache' => false,
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($c['router'], $basePath));

    return $view;
};

<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

/*$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'front/home.twig', $args);
});*/

$app->get('/', function (Request $request, Response $response, array $args) {
    return $this->view->render($response, 'front/home.twig', []);
})->setName('login');

$app->post('/api/login', function (Request $request, Response $response, array $args) use ($app) {
    $email = $request->getAttribute('email');
    $password = $request->getAttribute('password');
    $req = ['email' => $email, 'password' => $password];

    $userModel = new \src\models\User($app->getContainer());
    $result = $userModel->login($req);

    if($result == null){
        return json_encode(array(
            "status" => "401",
            "message" => "Email-id or Password doesn't exist",
        ));
        return;
    }

    return json_encode(array(
        "status" => "201",
        "message" => "User logged in successfully",
        "users" => $result
    ));

})->setName('home');
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

/**
 * this is login page
 */
$app->get('/', function (Request $request, Response $response, array $args) use ($app) {
    return $this->view->render($response, 'front/home.twig', []);
})->setName('login');

/**
 * this is logout
 */
$app->get('/api/logout', function (Request $request, Response $response, array $args) use ($app) {

    // check logged in
    if(isset($_COOKIE['token'])) {
        setcookie( 'token', null, time() - 10000000);
        setcookie( 'user', null, time() - 10000000);

        return json_encode(array(
            "status" => "201",
            "message" => "User logged out successfully"
        ));
    } else {
        return json_encode(array(
            "status" => "409",
            "message" => "Email-id or Password doesn't exist",
        ));
    }

})->setName('logout');

/**
 * this is login process page and create authentication token for successive operations api call
 */
$app->post('/api/login', function (Request $request, Response $response, array $args) use ($app) {
    $allPostPutVars = $request->getParsedBody();
    $email = $allPostPutVars['email'];
    $password = $allPostPutVars['password'];
    $req = ['email' => $email, 'password' => $password];

    $userModel = new \src\models\User($app->getContainer());
    $result = $userModel->login($req);

    if($result == null){
        return json_encode(array(
            "status" => "409",
            "message" => "Email-id or Password doesn't exist",
        ));
    }

    return json_encode(array(
        "status" => "201",
        "message" => "User logged in successfully",
        "data" => $result
    ));

})->setName('home');

/**
 * this is user dashboard
 */
$app->get('/home', function (Request $request, Response $response, array $args) use ($app) {
    // check logged in
    if(isset($_COOKIE['token'])) {
        return $this->view->render($response, 'front/dashboard.twig', []);
    }else{
        return $response->withRedirect('/');
    }

})->setName('dashboard');


// we need to change and follow route and also need the same route for any admin page
$app->post('/api/page1', function (Request $request, Response $response, array $args) use ($app) {
    // check logged in
    if(!isset($_COOKIE['token'])) {
        return $response->withRedirect('/');
    }

    // here model function call

    // responses
    return $this->view->render($response, 'front/page1.twig', []);

})->setName('page1');

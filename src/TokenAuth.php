<?php

/**
 * Class TokenAuth
 * do not change this class unless no other way as it will affect every api call
 */
class TokenAuth {

    protected $whiteList;
    protected $container;

    /**
     * TokenAuth constructor.
     * @param $container
     */
    public function __construct($container) {
        //Define the urls that you want to exclude from Authentication, aka public urls
        $this->whiteList = array('\/', '\/api\/login');
        $this->container = $container;

    }

    /**
     * call at every api 
     * @param $request
     * @param $response
     * @param $next
     */
    public function __invoke($request, $response, $next)
    {
        $verified = false;
        $headers = $request->getHeaders();
        
        if(!isset($headers['HTTP_AUTHORIZATION']) || empty($headers['HTTP_AUTHORIZATION'])) {
            $cookies = $headers['HTTP_COOKIE'];

            foreach ($cookies as $cookie) {
                $data = explode('=', $cookie);
                if($data[0] == 'token'){
                    if(!empty($this->verifyTokenAuth($data[1]))) {
                        $verified = true;
                        break;
                    }
                }
            }

        } else {
            $authToken = $headers['HTTP_AUTHORIZATION'][0];
            if(!empty($this->verifyTokenAuth($authToken))) {
                $verified = true;
            }
        }

        $whiteUrl = $this->isPublicUrl($_SERVER['REQUEST_URI']);

        // not public url but token verified
        if ($whiteUrl == 0 && $verified) {

            $response = $next($request, $response);
            //$response->getBody()->write('AFTER');
        } elseif ($whiteUrl > 0) {
            $response = $next($request, $response);
        } else {
            return $this->denyAccess();
        }
        
        return $response;
    }

    /**
     * @param $token
     * @return array
     */
    public function verifyTokenAuth($token)
    {
        // get validated
        $usrObj = new \src\models\User($this->container);
        $verificationResult = $usrObj->verifyToken($token);

        return $verificationResult;
    }

    /**
     * @param $url
     * @return int
     */
    public function isPublicUrl($url) {
        $patterns = implode('|', $this->whiteList);
        $matches = null;
        preg_match("/". $patterns ."/i", $url, $matches);
        //print_r($matches);
        return count($matches);
    }

    /**
     * denying
     */
    public function denyAccess() {
        $res = $this->app->response();
        $res->status(401);
    }

}

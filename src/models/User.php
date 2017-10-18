<?php
/**
 * Created by PhpStorm.
 * User: mobilution
 * Date: 17/10/17
 * Time: 5:34 PM
 */

namespace src\models;


class User
{
    protected $pdo;

    public function __construct($container)
    {
        $this->pdo = $container->get('PDO');
    }

    public function login($request)
    {
        try {

            $email = $request["email"];
            $password = $request["password"];

            $sql = "SELECT * from users where email = :email and password = :password";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_OBJ);

        } catch( PDOExecption $e ) {
            error_log($e->getMessage());
        }

        return $result;
    }
}
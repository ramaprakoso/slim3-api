<?php

namespace DDSControllers;

use \Psr\Container\ContainerInterface as Container;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \DDSModels\CustomerModel; 
use \Illuminate\Database\Capsule\Manager as DB;

class LoginController
{
    protected $container; 

    public function __construct(Container $container)
    {
        $this->container = $container; 
    }

    public function postLogin(Request $request, Response $response, $next)
    {
        $data = $request->getParsedBody(); //show all request 

        // $user_rows = CustomerModel::where('username', $data['username'])
        //                          ->where('password', $data['password'])
        //                          ->count(); 

        $user_rows = CustomerModel::where('username', $data['username'])
                    ->where('password', $data['password'])
                    ->first();         
                    
        // var_dump($user_rows); exit; 

        $token = rand(); 

        if($user_rows !== null){
            // $user_rows['customerNumber']; 

            // var_dump($user_rows['customerNumber']);
            // exit; 
            
            $result = array(
                "status" => true,
                "message" => "Login Berhasil",
            );

            if($user_rows['token'] == null){
                CustomerModel::where('customerNumber', $user_rows['customerNumber'])
                ->update([
                    'token' => $token
                ]); 
            }

            return $response->withStatus(200)->withJson($result);

        } else {
            $result = array(
                "status" => false,
                "message" => "Password Salah",
                "data" => []
            );
            return $response->withStatus(401)->withJson($result);
        }

        // return $response->withJson(["status" => "Unauthorized"], 401);
        // return $response->withStatus(401)->withJson($result);
    }
}   
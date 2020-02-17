<?php

namespace DDSControllers;

use \Psr\Container\ContainerInterface as Container;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \DDSModels\CustomerModel; 
use \Illuminate\Database\Capsule\Manager as DB;

class RegisterController
{
    protected $container; 

    public function __construct(Container $container)
    {
        $this->container = $container; 
    }

    public function postRegister(Request $request, Response $response, $next)
    {
        $data = $request->getParsedBody(); //show all request 
        
        $token = rand(); 

        $data = array(
            "customerNumber" => $data['customerNumber'],
            "customerName" => $data['customerName'],
            "username" => $data['username'],
            "password" => $data['password'],
            "phone" => $data['phone'],
            "addressLine1" => $data['addressLine1'],
            "city" => $data['city'],
            "state" => $data['state'],
            "token" => $token,
            "created_at" => date("Y-m-d h:i:s") 
        ); 
        
        $insert_customer = CustomerModel::insert($data); 

        if($insert_customer){
            $response->getBody()->write(200);

        } else {
            $response->getBody()->write(404);

        }
        return $response;         
    }
}
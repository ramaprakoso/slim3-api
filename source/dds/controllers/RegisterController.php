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

    public function __invoke(Request $request, Response $response, $next)
    {
        $customers = $request->getParsedBody(); //show all request 
        $data = array(
            "customerNumber" => $customers['customerNumber'],
            "customerName" => $customers['customerName'],
            "username" => $customers['username'],
            "password" => $customers['password'],
            "phone" => $customers['phone'],
            "addressLine1" => $customers['addressLine1'],
            "city" => $customers['city'],
            "state" => $customers['state'],
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
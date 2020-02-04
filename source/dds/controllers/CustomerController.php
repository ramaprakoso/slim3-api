<?php

namespace DDSControllers;

use \Psr\Container\ContainerInterface as Container;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \DDSModels\CustomerModel; 
use \Illuminate\Database\Capsule\Manager as DB;

class CustomerController
{
    /* just dont remove */
    protected $container;
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /* start your controller action here */
    public function getCustomerList(Request $request, Response $response)
    {
        // $return = [
        //     'status' => 'success',
        //     'tests' => [],
        // ];

        // $tests = DB::connection('test')->select('
        // SELECT 
        //     customerName,
        //     contactLastName,
        //     contactFirstName,
        //     phone
        // FROM 
        //     customers
        // ORDER BY 
        //     customerNumber
        // ');
        
        // if ($tests !== null && sizeof($tests) > 0) {
        //     foreach ($tests as $test) {
        //         $return['tests'][] = $test;
        //     }
        // }

        // $response->getBody()->write(json_encode($return));
        // return $response;
        $tests = CustomerModel::select('customerName', 'contactLastName')
                                ->get();

        if ($tests !== null && sizeof($tests) > 0) {
            foreach ($tests as $test) {
                $return['tests'][] = $test;
            }
        }

        $response->getBody()->write(json_encode($return));
        return $response;
    }

    public function addCustomer(Request $request, Response $response){
        $customers = $request->getParsedBody(); 
        // var_dump($customers); exit; 

    }
}

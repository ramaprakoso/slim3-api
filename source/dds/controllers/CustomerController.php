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
        $return = [
            'status' => 'success',
            'datas' => [],
        ];

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

        $datas = CustomerModel::get();

        if ($datas !== null && sizeof($datas) > 0) {
            foreach ($datas as $data) {
                $return['datas'][] = $data;
            }
        }

        $response->getBody()->write(json_encode($return));
        return $response;
    }

    public function getCustomerById(Request $request, Response $response, $args){
        $id = $args['id']; 

        $return = [
            'status' => 'success',
            'datas' => [],
        ];

        $datas = CustomerModel::where('customerNumber', $id)
        ->get();

        if ($datas !== null && sizeof($datas) > 0) {
            foreach ($datas as $data) {
                $return['datas'][] = $data;
            }
        }

        $response->getBody()->write(json_encode($return));
        return $response;
    }

    public function addCustomer(Request $request, Response $response){
        $customers = $request->getParsedBody(); //show all request 
        $data = array(
            "customerNumber" => $customers['customerNumber'],
            "customerName" => $customers['customerName'],
            // "contactFirstName" => $customers['contactFirstName'],
            // "contactLastName" => $customers['contactLastName'],
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

    public function deleteCustomer(Request $request, Response $response, $args){
        $id = $args['id']; 
        $delete_customer = CustomerModel::where('customerNumber', $id)
          ->update([
            'deleted_at' => date('Y-m-d H:i:s'),
          ]);

        if($delete_customer){
            $response->getBody()->write(200);

        } else {
            $response->getBody()->write(404);

        }

        return $response; 
    }

    public function updateCustomer(Request $request, Response $response, $args){
        $id = $args['id']; 
        $customers = $request->getParsedBody(); 

        $update_customer = CustomerModel::where('customerNumber', $id)
          ->update([
            "customerName" => $customers['customerName'],
            "phone" => $customers['phone'],
            "addressLine1" => $customers['addressLine1'],
            "city" => $customers['city'],
            "state" => $customers['state'],
            'updated_at' => date('Y-m-d H:i:s'),
          ]);

        if($update_customer){
            $response->getBody()->write(200);

        } else {
            $response->getBody()->write(404);

        }

        return $response;   
    }
}

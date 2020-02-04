<?php 

    // $app->get('/', '\DDSControllers\IndexController:index')->add($request_verify);
    // $app->get('/customer/', '\DDSControllers\CustomerController:getCustomerList');
    // $app->get('/insertCustomer', '\DDSControllers\IndexController:phpinfo')->add($request_verify);

    $app->group('/customer', function () use ($app) {
        $app->get('/', '\DDSControllers\CustomerController:getCustomerList');
        $app->get('/{id}', '\DDSControllers\CustomerController:getCustomerById');
        $app->post('/', '\DDSControllers\CustomerController:addCustomer');
        $app->put('/{id}', '\DDSControllers\CustomerController:updateCustomer'); 
        $app->delete('/{id}', '\DDSControllers\CustomerController:deleteCustomer');  
    }); 
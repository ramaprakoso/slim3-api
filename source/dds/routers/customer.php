<?php 

    // $app->get('/', '\DDSControllers\IndexController:index')->add($request_verify);
    // $app->get('/customer/', '\DDSControllers\CustomerController:getCustomerList');
    // $app->get('/insertCustomer', '\DDSControllers\IndexController:phpinfo')->add($request_verify);

    $app->group('/customer', function () use ($app) {
        $app->get('/', '\DDSControllers\CustomerController:getCustomerList');
        $app->post('/', '\DDSControllers\CustomerController:addCustomer'); 
    }); 
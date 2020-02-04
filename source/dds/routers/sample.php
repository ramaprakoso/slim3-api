<?php
/*
 * app router just for example
 */

// $app->group('/sample', function () use ($request_verify) {

//     $app->get('/', '\DDSControllers\SampleController:getExample');
//     $app->get('/{sample_id:[0-9]*}', '\DDSControllers\SampleController:getSingleExample');
//     $app->post('/', '\DDSControllers\SampleController:postExample');
//     $app->put('/{sample_id:[0-9]*}', '\DDSControllers\SampleController:putExample');
//     $app->delete('/{sample_id:[0-9]*}', '\DDSControllers\SampleController:deleteExample');

// });


// $app->group('/sample', function () use ($request_verify) {

    $app->get('/', '\DDSControllers\SampleController:getExample');
    $app->get('/{sample_id:[0-9]*}', '\DDSControllers\SampleController:getSingleExample');
    $app->post('/', '\DDSControllers\SampleController:postExample');
    $app->put('/{sample_id:[0-9]*}', '\DDSControllers\SampleController:putExample');
    $app->delete('/{sample_id:[0-9]*}', '\DDSControllers\SampleController:deleteExample');

// });
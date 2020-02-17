<?php

namespace DDSControllers;

use \Psr\Container\ContainerInterface as Container;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \DDSModels\CustomerModel; 
use \Illuminate\Database\Capsule\Manager as DB;
use Lcobucci\JWT\Signer\Key;
use \Illuminate\Support\Facades\Redis; 



class LogoutController
{
    protected $container; 

    public function __construct(Container $container)
    {
        $this->container = $container; 
    }

    public function postLogout(){
        $this->container->redis->del(); 
    }
}   
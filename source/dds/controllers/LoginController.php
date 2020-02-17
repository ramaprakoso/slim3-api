<?php

namespace DDSControllers;

use \Psr\Container\ContainerInterface as Container;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \DDSModels\CustomerModel; 
use \Illuminate\Database\Capsule\Manager as DB;
use Lcobucci\JWT\Signer\Key;


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

        $return = [
            'status' => 'failed',
            'message' => 'Invalid combination email & password.'
        ];

        $username = $data['username']; 
        $password = md5($data['password']);

        //basic auth 
        $user_rows = CustomerModel::where('username', $username)
                    ->where('password', $password)
                    ->first();         
        
        
        
        if($user_rows !== null){
            $request_ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
            $request_agent = $_SERVER['HTTP_USER_AGENT']; 

            $uuid = $this->container['utils']->generateuuid4();
            
            $time_before = time() + 0; 
            $time_expired = time() + $this->container['settings']['jwt']['expiration']; 

            /* Store Token */

            // $sysauth = new \DDSModels\CustomerModel(); 
            // $sysauth->uuid = $uuid; 
            // $sysauth->ip = $request_ip; 
            // $sysauth->token_expired = $time_expired; 
            // $sysauth->save(); 

            $signer = new \Lcobucci\JWT\Signer\Rsa\Sha256(); 
            // $signer = new \Lcobucci\JWT\Signer\Keychain(); 
            $privateKey = new Key('file://'. DDS_PATH . DIR_SEP . 'rsa' . DIR_SEP . 'private.rsa'); 

            $token = (new \Lcobucci\JWT\Builder())
            ->setIssuer($this->container['settings']['jwt']['issuer'])
            ->setAudience($this->container['settings']['jwt']['audience'])
            ->setId($uuid, true)
            ->setIssuedAt(time())
            ->setNotBefore($time_before)
            ->setExpiration($time_expired)
            ->set('uid', 1)
            ->sign($signer,  $privateKey)
            ->getToken(); 

            $return['status'] = 'success';
            $return['message'] = null;
            $return['token'] = (string) $token;

            $result = array(
                "status" => true,
                "message" => "Login Berhasil",
            );

            return $response->getBody()->write( json_encode( $return ) );

        } else {
            $result = array(
                "status" => false,
                "message" => "Password Salah",
                "data" => []
            );
            return $response->withStatus(401)->withJson($result);
        }

        

    }
}   
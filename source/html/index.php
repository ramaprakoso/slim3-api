<?php
/*
 * development mode for show all error messaeg
 */
error_reporting(E_ALL);

date_default_timezone_set('Asia/Jakarta');

define('DIR_SEP', DIRECTORY_SEPARATOR); // define separator '/'
define('DEFAULT_PATH', dirname(__DIR__)); // define path  '/application/source'
define('DDS_PATH', DEFAULT_PATH . DIR_SEP . 'dds'); // define '/application/source/dds'
define('PUBLIC_PATH', DEFAULT_PATH . DIR_SEP . 'public'); //define public path '/application/source/public'

require DEFAULT_PATH . DIR_SEP . 'vendor' . DIR_SEP . 'autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App(require DDS_PATH . DIR_SEP . 'settings.php');

/*
 * create initial container
 */
$container = $app->getContainer();

/*
 * initial db connection container
 */
$capsule = new \Illuminate\Database\Capsule\Manager;
// $capsule->addConnection($container['settings']['db_core'], 'core');
$capsule->addConnection($container['settings']['db_test'], 'test');
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};

$redis = new \Predis\Client(array(
    'scheme' => $container['settings']['redis']['scheme'],
    'host' => $container['settings']['redis']['host'],
    'port' => $container['settings']['redis']['port']
));

$container['redis'] = function ($container) use ($redis) {
    return $redis;
};


/*
 * error not found handler
//  */
$container['notFoundHandler'] = function ($container) {
    return function (Request $request, Response $response) use ($container) {
        return $container['response']
            ->withStatus(404)
            ->withHeader('Content-type', 'application/json')
            ->write(json_encode( [
                'status' => 'failed',
                'message' => 'Page not found'
            ] ));
    };
};

$basic_auth = function($request, $response, $next) {

    $request_authorization = $_SERVER["HTTP_AUTHORIZATION"];

    if (
        strlen($request_authorization) > strlen('Bearer') &&
        substr(strtolower($request_authorization), 0, strlen('Bearer')) == strtolower('Bearer')
    ){
        $signer = new \Lcobucci\JWT\Signer\Rsa\Sha256();
        $publicKey = new \Lcobucci\JWT\Signer\Key('file://'. DDS_PATH . DIR_SEP . 'rsa' . DIR_SEP . 'public.rsa'); 
        
        try {
            $token = (new \Lcobucci\JWT\Parser())->parse((string) str_replace('Bearer' . ' ', '', $request_authorization));

            $verify_data = new \Lcobucci\JWT\ValidationData();
            $verify_data->setIssuer( $container['settings']['jwt']['issuer'] );
            $verify_data->setAudience( $container['settings']['jwt']['audience'] );
            $verify_data->setCurrentTime(time());
            
            if (
                $token->verify($signer, $publicKey) &&
                $token->validate($verify_data)
            ) {

                //get token from redis
                $authjwt = $this->redis->get($token->getHeader('jti'));
                if ($authjwt !== null) {
                    $response = $next($request, $response);
                }
                return $response; 

            } else {
                $result = array(
                    "status" => false,
                    "message" => "Unauthorized",
                );
                return $response->withStatus(401)->withJson($result);
            }
        } catch (\Exception $e) { }
    }
    return false;
};


$container['utils'] = function ($container) {
    class UtilsClass {

        public function __construct(\Psr\Container\ContainerInterface $container)
        {
            $this->container = $container;
        }

        /*
         * generator uuid version 4
         */
        public function generateuuid4() {
            try {
                return strtolower(\Ramsey\Uuid\Uuid::uuid4()->toString());
            } catch (\Ramsey\Uuid\Exception\UnsatisfiedDependencyException $e) {
                return null;
            }
        }

    }
    return new UtilsClass($container);
};

require DDS_PATH . DIR_SEP . 'routers.php';

// $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function(Request $request, Response $response) {
//     $handler = $this->notFoundHandler;
//     return $handler($request, $response);
// });

/*
 * go and fly
 */
$app->run();

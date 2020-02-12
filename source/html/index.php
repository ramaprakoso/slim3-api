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

//basic auth test 
// $basic_auth = function ($request, $response, $next) {
//     $response->getBody()->write('BEFORE');
//     $response = $next($request, $response);
//     $response->getBody()->write('AFTER');
//     return $response;
// };

$basic_auth = function($request, $response, $next) {

    $request_authorization = $_SERVER["HTTP_AUTHORIZATION"];

    $auth_check = \DDSModels\CustomerModel::where('token', $request_authorization)
    ->whereNotNull('token')
    ->count();
    
    if($auth_check > 0){
        $response = $next($request, $response);
        return $response; 
    }

    $result = array(
        "status" => false,
        "message" => "Auth Failed ..."
    );
    
    return $response->withStatus(401)->withJson($result);
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

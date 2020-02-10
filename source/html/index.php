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
 
// var_dump(DEFAULT_PATH); 
// var_dump(PUBLIC_PATH); 
// var_dump(DDS_PATH); 
// exit; 
/*
 * get true ip address request
 * ref : https://www.slimframework.com/docs/v3/cookbook/ip-address.html
 */
/*
$checkProxyHeaders = true;
$trustedProxies = ['10.0.0.1', '10.0.0.2'];
$app->add(new RKA\Middleware\IpAddress($checkProxyHeaders, $trustedProxies));
*/

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
 * register view container
 */
/*
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(DDS_PATH . DIR_SEP . 'views', [
        // 'cache' => DDS_PATH . DIR_SEP . 'views' . DIR_SEP . 'cache'
        'cache' => false,
    ]);
    return $view;
};
*/

/*
 * error respond handler
 */
// if ($container['settings']['environment'] == 'production' ) {
//     $container['errorHandler'] = function ($container) {
//         return function (Request $request, Response $response, $exception) use ($container) {
//             return $container['response']
//                 ->withStatus(500)
//                 ->withHeader('Content-type', 'application/json')
//                 ->write(json_encode( [
//                     'status' => 'failed',
//                     'message' => 'Ups something wrong :('
//                 ] ));
//         };
//     };
// }

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

/*
 * 405 Not Allowed Handler
 */
// $container['notAllowedHandler'] = function ($container) {
//     return function ($request, $response, $methods) use ($container) {
//         return $container['response']
//             ->withStatus(405)
//             ->withHeader('Allow', implode(', ', $methods))
//             ->withHeader('Content-type', 'text/html')
//             ->write('Method must be one of: ' . implode(', ', $methods));
//     };
// };


/*
 * cors handler
 */
// $app->options('/{routes:.+}', function (Request $request, Response $response, $args = []) {
//     return $response
//         ->withStatus(204)
//         ->withHeader('Access-Control-Max-Age', 1728000)
//         ->withHeader('Content-Type', 'text/plain; charset=utf-8')
//         ->withHeader('Content-Length', 0);
// });

/*
 * json respond handler
 */
// $app->add(function (Request $request, Response $response, callable $next) {
//     $response = $next($request, $response);
//     return $response
//         // ->withStatus(200)
//         ->withHeader('Access-Control-Allow-Origin', '*')
//         ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
//         ->withHeader('Access-Control-Allow-Headers', 'Accept, Authorization,DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range')
//         ->withHeader('Content-type', 'application/json')
//         ->withHeader('X-Content-Type-Options', 'nosniff')
//         ->withHeader('X-Frame-Options', 'SAMEORIGIN')
//         ->withHeader('X-Xss-Protection', '1; mode=block')
//         ->withHeader('Cache-Control', 'private, no-cache, no-store, proxy-revalidate, no-transform')
//         ->withHeader('Pragma', 'no-cache');
// });

/*
 * request verify
 */
// $container['request_verify'] = function ($container) {
//     $referer_domain = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : null;
//     $request_domain = null;
//     if ($referer_domain != null) {
//         $parse_url = parse_url($referer_domain);
//         $request_domain = isset($parse_url['host']) ? $parse_url['host'] : null;
//         if ($request_domain != null && in_array($request_domain, $container['settings']['client_domains'])) {
//             return true;
//         }
//     }
//     return false;
// };

// $basic_auth = function (Request $request, Response $response, callable $next) use ($container) {
   
//     // $check = '\DDSControllers\LoginController:postLogin'; 

//     $params = $request->getQueryParams();
//     if($check !== false){
//         echo "true"; 
//         $response = $next($request, $response);
//     } else {
//         $response->withStatus(401)->getBody()->write( json_encode( [
//             'status' => 'failed',
//             'message' => 'You are not authorize.',
//         ]) );
//         echo "false"; 

//     }
//     return $response;
// };


/*
$admin_request_verify = function (Request $request, Response $response, callable $next) use ($container) {
    if ($container['request_verify'] !== false && $container['request_verify']['company_id'] == 0) {
        $response = $next($request, $response);
    } else {
        $response->withStatus(401)->getBody()->write( json_encode( [
            'status' => 'failed',
            'message' => 'You are not authorize.',
        ]) );
    }
    return $response;
};
*/

/*
 * authorization
 */
// $container['authorization'] = function ($container) {
//     $request_authorization = $_SERVER["HTTP_AUTHORIZATION"];
//     if (
//         strlen($request_authorization) > strlen('Bearer') &&
//         substr(strtolower($request_authorization), 0, strlen('Bearer')) == strtolower('Bearer')
//     ){

//         $signer = new \Lcobucci\JWT\Signer\Rsa\Sha256();
//         $keychain = new \Lcobucci\JWT\Signer\Keychain();

//         try {

//             $token = (new \Lcobucci\JWT\Parser())->parse((string) str_replace('Bearer' . ' ', '', $request_authorization));

//             $verify_data = new \Lcobucci\JWT\ValidationData();
//             $verify_data->setIssuer( $container['settings']['jwt']['issuer'] );
//             $verify_data->setAudience( $container['settings']['jwt']['audience'] );
//             $verify_data->setCurrentTime(time());

//             if (
//                 $token->verify($signer, $keychain->getPublicKey('file://'. DDS_PATH . DIR_SEP . 'rsa' . DIR_SEP . 'public.rsa')) &&
//                 $token->validate($verify_data)
//             ) {

//                 $authjwt = \DDSModels\CORESysoutjwtModel::where('uuid', $token->getHeader('jti'))->first();
//                 if ($authjwt !== null) {

//                     //$request_ip = $request->getAttribute('ip_address');
//                     $request_ip = 'UNKNOWN';
//                     if (isset($_SERVER['HTTP_CLIENT_IP']))
//                         $request_ip = $_SERVER['HTTP_CLIENT_IP'];
//                     else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
//                         $request_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//                     else if(isset($_SERVER['HTTP_X_FORWARDED']))
//                         $request_ip = $_SERVER['HTTP_X_FORWARDED'];
//                     else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
//                         $request_ip = $_SERVER['HTTP_FORWARDED_FOR'];
//                     else if(isset($_SERVER['HTTP_FORWARDED']))
//                         $request_ip = $_SERVER['HTTP_FORWARDED'];
//                     else if(isset($_SERVER['REMOTE_ADDR']))
//                         $request_ip = $_SERVER['REMOTE_ADDR'];
//                     $request_agent = $_SERVER['HTTP_USER_AGENT']; // $container['request']->getHeader('HTTP_USER_AGENT')[0];

//                     if ($authjwt->ip == $request_ip && $authjwt->agent == $request_agent) {

//                         $schedule_data = null;
//                         $test_data = null;

//                         $auth_data = unserialize($authjwt->data);

//                         if(isset($auth_data['schedule']) && is_array($auth_data['schedule'])){
//                             $schedule_data = $auth_data['schedule'];
//                         }

//                         if(isset($auth_data['test']) && is_array($auth_data['test'])){
//                             $test_data = $auth_data['test'];
//                         }

//                         return [
//                             'uuid' => $token->getHeader('jti'),
//                             'token' => (string) $token,
//                             'schedule' => $auth_data['schedule'],
//                             'test' => $auth_data['test'],
//                         ];

//                     }

//                 }

//             }

//         } catch (\Exception $e) { }

//     }
//     return false;
// };

// $schedule_authorization = function (Request $request, Response $response, callable $next) use ($container) {
//     if ($container['authorization'] !== false && $container['authorization']['schedule'] != null) {
//         $response = $next($request, $response);
//     } else {
//         header('HTTP/1.1 401 Unauthorized');
//         header('Content-type: application/json');
//         echo json_encode( [
//             'status' => 'failed',
//             'message' => 'You are not authorize.',
//         ]);
//         exit;
//     }
//     return $response;
// };

// $test_authorization = function (Request $request, Response $response, callable $next) use ($container) {
//     if ($container['authorization'] !== false && $container['authorization']['test'] != null) {
//         $response = $next($request, $response);
//     } else {
//         header('HTTP/1.1 401 Unauthorized');
//         header('Content-type: application/json');
//         echo json_encode( [
//             'status' => 'failed',
//             'message' => 'You are not authorize.',
//         ]);
//         exit;
//     }
//     return $response;
// };

/*
 * utils generator
 */
// $container['utils'] = function ($container) {
//     class UtilsClass {

//         public function __construct(\Psr\Container\ContainerInterface $container)
//         {
//             $this->container = $container;
//         }

//         /*
//          * generator uuid version 4
//          */
//         public function generateuuid4() {
//             try {
//                 return strtolower(\Ramsey\Uuid\Uuid::uuid4()->toString());
//             } catch (\Ramsey\Uuid\Exception\UnsatisfiedDependencyException $e) {
//                 return null;
//             }
//         }

//     }
//     return new UtilsClass($container);
// };

/*
 * load router config
 */
require DDS_PATH . DIR_SEP . 'routers.php';

// $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function(Request $request, Response $response) {
//     $handler = $this->notFoundHandler;
//     return $handler($request, $response);
// });

/*
 * go and fly
 */
$app->run();

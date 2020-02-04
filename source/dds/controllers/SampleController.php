<?php
namespace DDSControllers;
use \Psr\Container\ContainerInterface as Container;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Illuminate\Database\Capsule\Manager as DB;

class SampleController {
    /* just dont remove */
    protected $container;
    public function __construct(Container $container) {
        $this->container = $container;
    }

    /* start your controller action here */

    public function getExample(Request $request, Response $response, array $args) {

        $return = [
            'status' => 'success',
            'samples' => [],
        ];

        for ($i = 1; $i <= 100; $i++) {
            $return['samples'][] = [
                'text' => 'just sample index text '.$i,
                'number' => $i,
            ];
        }

        $response->getBody()->write( json_encode( $return ) );
        return $response;

    }

    // public function getSingleExample(Request $request, Response $response, array $args) {

    //     $return = [
    //         'status' => 'success',
    //         'sample' => [
    //             'text' => 'just sample index text '.$args['sample_id'],
    //             'number' => $args['sample_id'],
    //         ],
    //     ];

    //     $response->getBody()->write( json_encode( $return ) );
    //     return $response;

    // }

    // public function postExample(Request $request, Response $response, array $args) {

    //     $return = [
    //         'status' => 'success',
    //     ];


    //     /*
    //     $contoh = new \DDSModels\COREContohModel();
    //     // $contoh->setConnection($this->container['db_core']->getConnection()->getPdo());
    //     $contoh->contoh_text = $request->getParam('text');
    //     $contoh->save();
    //     */

    //     $contoh = DB::connection('test')->select('SELECT * FROM contoh');

    //     // $contoh = DB::connection('core')->select('SELECT * FROM contoh');
    //     /*
    //     echo '<pre>';
    //     print_r($contoh);
    //     echo '</pre>';
    //     exit;
    //     */

    //     $return['contoh_id'] = $contoh->contoh_id;

    //     $response->getBody()->write( json_encode( $return ) );
    //     return $response;

    // }

    // public function putExample(Request $request, Response $response, array $args) {

    //     $return = [
    //         'status' => 'success',
    //     ];

    //     // sample capture data
    //     // $args['sample_id']
    //     // $request->getParam('text');

    //     $response->getBody()->write( json_encode( $return ) );
    //     return $response;

    // }

    // public function deleteExample(Request $request, Response $response, array $args) {

    //     $return = [
    //         'status' => 'success',
    //     ];

    //     // sample capture data
    //     // $args['sample_id']

    //     $response->getBody()->write( json_encode( $return ) );
    //     return $response;

    // }

}

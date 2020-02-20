<?php

use \Psr\Http\Message\ServerRequestInterface as Request;

use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

require '../includes/DbOperations.php';

require '../includes/DbConnect.php';

$app = new \Slim\App([

    'settings' => [

        'displayErrorDetails' => true

    ]

]);

$app->get('/getTwoWeeksCrime', function (Request $request, Response $response) {

    $db = new DbOperations;

    $crimes = $db->lastTwoWeeksCrimes();

    $response_data = array();

    $response_data['crimes'] = $crimes;

    $response->write(json_encode($response_data));

    return $response

        ->withHeader('Content-type', 'application/json', 'charset=utf-8')

        ->withStatus(200);
});
$app->get('/getCounties', function (Request $request, Response $response) {

    $db = new DbOperations;

    $counties = $db->getCounties();

    $response_data = array();

    $response_data['error'] = false;

    $response_data['counties'] = $counties;

    $response->write(json_encode($response_data));

    return $response

        ->withHeader('Content-type', 'application/json', 'charset=utf-8')

        ->withStatus(200);
});
$app->get('/getGeoJSON', function (Request $request, Response $response) {

    $db = new DbOperations;

    $coordinates = $db->generateGeoJSON();

    $response_data = array();

    $response_data['coordinates'] = $coordinates;

    $response->write(json_encode($response_data));

    return $response

        ->withHeader('Content-type', 'application/json', 'charset=utf-8')

        ->withStatus(200);
});
$app->get('/getLocationData', function (Request $request, Response $response) {

    $db = new DbOperations;

    $coordinates = $db->getLocationData();

    $response_data = array();

    $response_data['coordinates'] = $coordinates;

    $response->write(json_encode($response_data));

    return $response

        ->withHeader('Content-type', 'application/json', 'charset=utf-8')

        ->withStatus(200);
});
$app->get('/getLocationDataId', function (Request $request, Response $response) {
    $id = $request->getQueryParam('id');

    if (isset($id)) {

        $db = new DbOperations;

        $info = $db->getEvent($id);


        $response_data['info'] = $info;

        $response->write(json_encode($response_data));

        return $response

            ->withHeader('Content-type', 'application/json', 'charset=utf-8')

            ->withStatus(200);
    }
});

function haveEmptyParameters($required_params, $request, $response)
{

    $error = false;

    $error_params = '';

    $request_params = $request->getParsedBody();



    foreach ($required_params as $param) {

        if (!isset($request_params[$param]) || strlen($request_params[$param]) <= 0) {

            $error = true;

            $error_params .= $param . ', ';
        }
    }



    if ($error) {

        $error_detail = array();

        $error_detail['error'] = true;

        $error_detail['message'] = 'Required parameters ' . substr($error_params, 0, -2) . ' are missing or empty';

        $response->write(json_encode($error_detail));
    }

    return $error;
}
$app->run();

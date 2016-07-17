<?php

    error_reporting(-1);
    ini_set('display_errors', 'On');

    require_once '../include/db_handler.php';
    require '.././libs/Slim/Slim.php';

    \Slim\Slim::registerAutoloader();

    $app = new \Slim\Slim();


    // All Brand
    $app->get('/user/', function() use ($app) {

        $db = new DbHandler();
        $response = $db->getAllUsers();
        echoResponse(200, $response);
    });


    // User by Id
    $app->get('/user/:id', function($id) use ($app) {

        $db = new DbHandler();
        $response = $db->getUserById($id);
        echoResponse(200, $response);
    });


    //Insert Brand
    $app->post('/user/add', function() use ($app) {

        verifyRequiredParams(array('name', 'lastname', 'email', 'password'));

        $name = $app->request->post('name');
        $lastname = $app->request->post('lastname');
        $email = $app->request->post('email');
        $password = $app->request->post('password');

        $db = new DbHandler();
        $response = $db->addUser($name, $lastname, $email, $password);

        echoResponse(200, $response);
    });


    //Login
    $app->post('/user/login', function() use ($app) {

        verifyRequiredParams(array('email', 'password'));

        $email = $app->request->post('email');
        $password = $app->request->post('password');

        $db = new DbHandler();
        $response = $db->login($email, $password);

        echoResponse(200, $response);
    });



    function echoResponse($status_code, $response) {
        $app = \Slim\Slim::getInstance();

        $app->status($status_code);
        $app->contentType('application/json');

        echo json_encode($response);
    }

    function verifyRequiredParams($required_fields) {
        $error = false;
        $error_fields = "";
        $request_params = $_REQUEST;

        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $app = \Slim\Slim::getInstance();
            parse_str($app->request()->getBody(), $request_params);
        }
        foreach ($required_fields as $field) {
            if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
                $error = true;
                $error_fields .= $field . ', ';
            }
        }

        if ($error) {
            $response = array();
            $app = \Slim\Slim::getInstance();

            $meta = array();
            $meta["status"] = "error";
            $meta["code"] = "1000";
            $meta["message"] = 'Campo requerido ' . substr($error_fields, 0, -2) . ', se encuentra vacio o nulo';
            $response["_meta"] = $meta;
            echoResponse(400, $response);
            $app->stop();
        }
    }

    $app->run();

?>
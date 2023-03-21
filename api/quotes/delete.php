<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization.X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';


    //Instantiate DB and CONNECT
    $database = new Database();
    $db = $database->connect();


    //Instantiate blog quote object
    $quo = new Quote($db);

    //Get the raw posted data
    $data = json_decode(file_get_contents("php://input"));

    //data is not set
    if(!isset($data->id)){
        echo(json_encode(array('message' => 'Missing parameters')));
        exit();
    }

    //SET ID TO UPDATE
    $quo->id = $data->id;

    //delete post
    if($quo->delete()){
        echo json_encode(
            array('message' => 'Quote Deleted')
        );
    } else {
        echo json_encode(
            array('message' => 'Quote Not Deleted')
        );
    }
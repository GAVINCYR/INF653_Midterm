<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization.X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';


    //Instantiate DB and CONNECT
    $database = new Database();
    $db = $database->connect();


    //Instantiate blog quote object
    $aut = new Author($db);

    //Get the raw posted data
    $data = json_decode(file_get_contents("php://input"));

    //if data is not all set, send error message and exit
    if ( !isset($data->author) )
    {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    } else {
        $aut->author = $data->author;
        $aut->create();
        echo json_encode(array('id' => $db->lastInsertId(), 'author'=>$aut->author));

    }

<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
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

    //data is not set
    if(!isset($data->id) || !isset($data->author)){
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }
    //SET ID TO UPDATE
    $aut->id = $data->id;
    $aut->author = $data->author;

    //update author
    if($aut->update()){
        echo json_encode(
            array(
                'id' => $aut->id,
                'author' => $aut->author
        ));
    } else {
        echo json_encode(
            array(
                'message' => 'author_id Not Found'
        ));
    }
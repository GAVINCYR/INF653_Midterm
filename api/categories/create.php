<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization.X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';


    //Instantiate DB and CONNECT
    $database = new Database();
    $db = $database->connect();


    //Instantiate blog quote object
    $cat = new Category($db);

    //Get the raw posted data
    $data = json_decode(file_get_contents("php://input"));

    //if data is not all set, send error message and exit
    if ( !isset($data->category) )
    {
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    } else {
        $cat->Category = $data->category;
        $cat->create();
        echo json_encode(array('id' => $db->lastInsertId(), 'category'=>$cat->category));

    }

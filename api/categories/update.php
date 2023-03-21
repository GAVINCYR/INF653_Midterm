<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
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

    //data is not set
    if(!isset($data->id) || !isset($data->category)){
        echo json_encode(array('message' => 'Missing Required Parameters'));
        exit();
    }
    //SET ID TO UPDATE
    $cat->id = $data->id;
    $cat->category = $data->category;

    //update category
    if($cat->update()){
        echo json_encode(
            array(
                'id' => $cat->id,
                'category' => $cat->category,
        ));
    } else {
        echo json_encode(
            array(
                'message' => 'category_id Not Found'
        ));
    }
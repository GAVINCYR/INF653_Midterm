<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';


    //Instantiate DB and CONNECT
    $database = new Database();
    $db = $database->connect();


    //Instantiate blog category object
    $cat = new Category($db);

    //GET ID
    $cat->id = isset($_GET['id']) ? $_GET['id'] : die();// gets the value of that id

    //GET category
   if( $cat->read_single()){
        echo json_encode(array(
            'id' => $cat->id,
            'category' => $cat->category
        ));
   }
//cannot find id
   else {
    echo json_encode(array(
        'message' => 'category_id Not Found'
    ));
   }

<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';


    //Instantiate DB and CONNECT
    $database = new Database();
    $db = $database->connect();


    //Instantiate blog author object
    $aut = new Author($db);

    //GET ID
    $aut->id = isset($_GET['id']) ? $_GET['id'] : die();// gets the value of that id

    //GET author
   if( $aut->read_single()){
        echo json_encode(array(
            'id' => $aut->id,
            'author' => $aut->author
        ));
   }
//cannot find id
   else {
    echo json_encode(array(
        'message' => 'author_id Not Found'
    ));
   }
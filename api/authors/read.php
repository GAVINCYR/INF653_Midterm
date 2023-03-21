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

    //Blog author query
    $result = $aut->read();

    //Get row count
    $num = $result->rowCount();

    //Check if any categories
    if($num>0){
        // author array
        $author_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $author_item = array(
                'id' => $id,
                'author' => $author
            );

            //push to "data
            array_push($author_arr, $author_item);
        }

        //Turn to JSON & output
        echo json_encode($author_arr);
    } else {
        //NO author
        echo json_encode(
            array('message' => 'author_id Not Found')
        );
    }
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

    //Blog category query
    $result = $cat->read();

    //Get row count
    $num = $result->rowCount();

    //Check if any categories
    if($num>0){
        // cat array
        $category_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $category_item = array(
                'id' => $id,
                'category' => $category
            );

            //push to "data
            array_push($category_arr, $category_item);
        }

        //Turn to JSON & output
        echo json_encode($category_arr);
    } else {
        //NO category
        echo json_encode(
            array('message' => 'category_id Not Found')
        );
    }

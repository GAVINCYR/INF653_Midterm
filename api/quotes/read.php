<?php
    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';


    //Instantiate DB and CONNECT
    $database = new Database();
    $db = $database->connect();


    //Instantiate blog quote object
    $quotes = new Quote($db);

    //get data if only it is set
    if (isset($_GET['author_id'])){
        $quotes->author_id = $_GET['author_id'];
    }
    if (isset($_GET['category_id'])){
        $quotes->category_id = $_GET['category_id'];
    }

    //Blog quote query
    $result = $quotes->read();

    //Get row count
    $num = $result->rowCount();

    //Check if any quotes
    if($num>0){
        // quote array
        $quotes_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $quote_item = array(
                'id' => $id,
                'author' => $author,
                'quote' => html_entity_decode($quote),
                'category' => $category
            );

            //push to "data
            array_push($quotes_arr, $quote_item);
        }

        //Turn to JSON & output
        echo json_encode($quotes_arr);
    } else {
        //NO quotes
        echo json_encode(
            array('message' => 'No Quotes Found')
        );
    }

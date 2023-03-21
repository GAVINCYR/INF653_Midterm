<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';
  $database = new Database();
  $db = $database->connect();

  $quote = new Quote($db);

  $data = json_decode(file_get_contents("php://input"));


  if (!property_exists($data, 'quote') || !property_exists($data, 'authorId') || 
        !property_exists($data, 'categoryId') || !property_exists($data, 'id')) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    return;
  }
  
  $quote->id = $data->id;
  $quote->quote = $data->quote;
  $quote->authorId = $data->authorId;
  $quote->categoryId = $data->categoryId;

  $val = $quote->update();
  if($val >= 0) {
    echo json_encode(
      array(
        'id' => $quote->id,
        'quote' => $quote->quote,
        'authorId' => $quote->authorId,
        'categoryId' => $quote->categoryId
      )
    );
  } else if($val == -2){
    echo json_encode(
      array('message' => 'authorId Not Found')
    );
  }else if($val == -3){
    echo json_encode(
      array('message' => 'categoryId Not Found')
    );
  }else {
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  }
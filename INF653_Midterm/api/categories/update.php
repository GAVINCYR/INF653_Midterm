<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';
  $database = new Database();
  $db = $database->connect();

  $category = new Category($db);

  $data = json_decode(file_get_contents("php://input"));

  if (!property_exists($data, 'category') || !property_exists($data, 'id')) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    return;
  }

  $category->id = $data->id;

  $category->category = $data->category;

  if($category->update()) {
    echo json_encode(
      array('id' => $category->id, 'category' => $category->category)
    );
  } else {
      $category->category = -1;
      $category->read_single(); 
      if($category->category != -1){
        echo json_encode(
          array('id' => $category->id, 'category' => $category->category)
        );
      }else{
      echo json_encode(
        array('message' => 'CategoryId Not Found')
      );
    }
  }
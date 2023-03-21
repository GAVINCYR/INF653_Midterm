<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Author.php';
  $database = new Database();
  $db = $database->connect();

  $author = new Author($db);

  $data = json_decode(file_get_contents("php://input"));

  if (!property_exists($data, 'author') || !property_exists($data, 'id')) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    return;
  }

  $author->id = $data->id;

  $author->author = $data->author;

  if($author->update()) {
    echo json_encode(
      array('id' => $author->id, 'author' => $author->author)
    );
  } else {
      $author->author = -1;
      $author->read_single(); 
      if($author->author != -1){
        echo json_encode(
          array('id' => $author->id, 'author' => $author->author)
        );
      }else{
      echo json_encode(
        array('message' => 'authorId Not Found')
      );
    }
  }
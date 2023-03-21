<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';
  $database = new Database();
  $db = $database->connect();

  $quote = new Quote($db);
  
  $quote->categoryId = isset($_GET['categoryId']) ? $_GET['categoryId'] : -1;
  $quote->authorId = isset($_GET['authorId']) ? $_GET['authorId'] : -1;

  if ($quote->categoryId != -1 && $quote->authorId != -1 ) {
    $result = $quote->readBoth();
  }else if ($quote->categoryId != -1) {
    $result = $quote->readCategory();
  }else if ($quote->authorId != -1 ) {
    $result = $quote->readAuthor();
  }else{
    $result = $quote->read();
  }

  $num = $result->rowCount();

  if($num > 0) {
        $quote_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);
          array_push($quote_arr, (object)[
            'id' => $id, 
            'quote' => $quote,
            'author' => $author,
            'category' => $category
          ]);
        }
        echo json_encode($quote_arr);

  } else {
    echo json_encode(
      array('message' => 'No Quotes Found')
    );
  }
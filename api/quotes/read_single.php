<?php
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';
  $database = new Database();
  $db = $database->connect();

  $quote = new Quote($db);

  $quote->id = isset($_GET['id']) ? $_GET['id'] : die();

  $quote->read_single();

  if($quote->quote){
    $quote_arr = array(
      'id' => $quote->id, 
      'quote' => $quote->quote,
      'author' => $quote->author,
      'category' => $quote->category
    );
    print_r(json_encode($quote_arr));
  }else{
    print_r(json_encode(array('message'=> 'No Quotes Found')));
  }
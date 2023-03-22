<?php
include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();

$quote = new Quote($db);

$quote->id = isset($_GET['id']) ? $_GET['id'] : die();

$quote->read_single();

if($quote->quote === false){
    echo json_encode(
        array('message' => 'No Quotes Found'));
}
else{
    print_r(json_encode($quote->quote));
}
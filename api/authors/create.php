<?php
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->connect();

$author = new Author($db);

$data = json_decode(file_get_contents("php://input"));

if(!get_object_vars($data) || !isset($data->author)){ //If there are no parameters
    echo json_encode(array('message' => 'Missing Required Parameters'));
}
else{
    $author->author = $data->author;

    if(!$author->create()){
        echo json_encode(array('message' => 'Author Not Created'));
    }
}
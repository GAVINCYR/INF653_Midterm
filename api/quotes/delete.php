<?php
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

$database = new Database();
$db = $database->connect();

$quote = new Quote($db);

$data = json_decode(file_get_contents("php://input"));

if(!get_object_vars($data) || !isset($data->id)){
    echo json_encode(array('message' => 'Missing Required Parameters'));
}
else{
    $quote->id = $data->id;

    if(!$quote->delete()){
        echo json_encode(array('message' => 'Quote Not Deleted'));
    }
}
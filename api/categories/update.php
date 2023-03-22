<?php
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();

$category = new Category($db);

$data = json_decode(file_get_contents("php://input"));

if(!get_object_vars($data) || !isset($data->id) || !isset($data->category)){
    echo json_encode(array('message' => 'Missing Required Parameters'));
}
else{
    $category->id = $data->id;
    $category->category = $data->category;

    if(!$category->update()){
        echo json_encode(array('message' => 'Category Not Updated'));
    }
}
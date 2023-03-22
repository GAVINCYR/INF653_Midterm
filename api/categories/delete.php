<?php
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();

$category = new Category($db);

$data = json_decode(file_get_contents("php://input"));

if(!get_object_vars($data) || !isset($data->id)){
    echo json_encode(array('message' => 'Missing Required Parameters'));
}
else{
    $category->id = $data->id;

    if(!$category->delete()){
        echo json_encode(array('message' => 'Category Not Deleted'));
    }
}
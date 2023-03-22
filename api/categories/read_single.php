<?php
include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();

$category = new Category($db);

$category->id = isset($_GET['id']) ? $_GET['id'] : die();

$category->read_single();

if($category->category === false){
    echo json_encode(array('message' => 'category_id Not Found'));
}
else{
    print_r(json_encode($category->category));
}

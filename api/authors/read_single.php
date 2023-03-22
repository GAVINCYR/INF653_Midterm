<?php
include_once '../../config/Database.php';
include_once '../../models/Author.php';

$database = new Database();
$db = $database->connect();

$author = new Author($db);

$author->id = isset($_GET['id']) ? $_GET['id'] :  die();

$author->read_single();

if($author->author === false){
    echo json_encode(array('message' => 'author_id Not Found'));
}
else{
    print_r(json_encode($author->author));
}
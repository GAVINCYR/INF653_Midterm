<?php
include_once '../../config/Database.php';
include_once '../../models/Category.php';

$database = new Database();
$db = $database->connect();

$category = new Category($db);

$result = $category->read();

$num = $result->rowCount();

if($num > 0){
	$category_arr = array();
	$category_arr['data'] = array();

	while($row = $result->fetch(PDO::FETCH_ASSOC)){
		extract($row);

		$category_item = array('id' => $id, 'category' => $category);

		array_push($category_arr['data'], $category_item);
	}

	echo json_encode($category_arr['data']);
}else{
	echo json_encode(
	array('message' => 'No Categories found'));
}

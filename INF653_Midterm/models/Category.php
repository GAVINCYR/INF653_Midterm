<?php
  class Category {
    private $conn;
    private $table = 'categories';

    public $id;
    public $category;

    public function __construct($db) {
      $this->conn = $db;
    }

    public function read() {
      $query = 'SELECT
                  id,
                  category
                FROM
                  ' . $this->table;

      $stmt = $this->conn->prepare($query);

      $stmt->execute();

      return $stmt;
    }

  public function read_single(){
    $query = 'SELECT
                id,
                category
              FROM
                ' . $this->table . '
              WHERE id = ?
              LIMIT 0,1';

      $stmt = $this->conn->prepare($query);

      $stmt->bindParam(1, $this->id);

      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if($row && $row['category']){
          $this->id = $row['id'];
          $this->category = $row['category'];
      }
  }

  public function create() {
    $query = 'INSERT INTO ' .
                $this->table . '
              SET
                category = :category';

  $stmt = $this->conn->prepare($query);

  $this->category = htmlspecialchars(strip_tags($this->category));

  $stmt-> bindParam(':category', $this->category);

  if($stmt->execute()) {

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $this->conn->lastInsertId();;
  }

  printf("Error: $s.\n", $stmt->error);

  return -1;
  }

  public function update() {
    $query = 'UPDATE ' .
                $this->table . '
              SET
                category = :category
              WHERE
              id = :id';

  $stmt = $this->conn->prepare($query);

  $this->category = htmlspecialchars(strip_tags($this->category));
  $this->id = htmlspecialchars(strip_tags($this->id));

  $stmt-> bindParam(':category', $this->category);
  $stmt-> bindParam(':id', $this->id);

  if($stmt->execute()) {
    return $stmt->rowCount() > 0;
  }

  printf("Error: $s.\n", $stmt->error);
  return false;
  }

  public function delete() {
    $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

    $stmt = $this->conn->prepare($query);

    $this->id = htmlspecialchars(strip_tags($this->id));

    $stmt-> bindParam(':id', $this->id);

    try {
      if($stmt->execute()) {
        return $stmt->rowCount();
      }
    } catch (\Throwable $th) {

    }
    return -1;
    }
  }
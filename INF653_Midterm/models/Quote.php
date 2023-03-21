<?php
  class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $authorId;
    public $categoryId;
    public $author;
    public $category;

    public function __construct($db) {
      $this->conn = $db;
    }

    public function read() {
      $query = 'SELECT quotes.id, quotes.quote, authors.author, categories.category
                FROM quotes
                INNER JOIN authors ON authors.id = quotes.authorId
                INNER JOIN categories ON categories.id = quotes.categoryId
                ORDER BY quotes.id';

      $stmt = $this->conn->prepare($query);

      $stmt->execute();

      return $stmt;
    }
    public function readAuthor() {
      $query = 'SELECT quotes.id, quotes.quote, authors.author, categories.category
                FROM quotes
                INNER JOIN authors ON authors.id = quotes.authorId
                INNER JOIN categories ON categories.id = quotes.categoryId
                WHERE quotes.authorId = ?
                ORDER BY quotes.id';

      $stmt = $this->conn->prepare($query);

      $stmt->bindParam(1, $this->authorId);

      $stmt->execute();

      return $stmt;
    }
    public function readCategory() {
      $query = 'SELECT quotes.id, quotes.quote, authors.author, categories.category
                FROM quotes
                INNER JOIN authors ON authors.id = quotes.authorId
                INNER JOIN categories ON categories.id = quotes.categoryId
                WHERE quotes.categoryId = ?
                ORDER BY quotes.id';

      $stmt = $this->conn->prepare($query);

      $stmt->bindParam(1, $this->categoryId);

      $stmt->execute();

      return $stmt;
    }
    public function readBoth() {
      $query = 'SELECT quotes.id, quotes.quote, authors.author, categories.category
                FROM quotes
                INNER JOIN authors ON authors.id = quotes.authorId
                INNER JOIN categories ON categories.id = quotes.categoryId
                WHERE quotes.categoryId = :categoryId and quotes.authorId = :authorId
                ORDER BY quotes.id';

      $stmt = $this->conn->prepare($query);

      $this->authorId = htmlspecialchars(strip_tags($this->authorId));
      $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));

      $stmt-> bindParam(':authorId', $this->authorId);
      $stmt-> bindParam(':categoryId', $this->categoryId);

      $stmt->execute();

      return $stmt;
    }

    public function read_single(){
      $query = 'SELECT quotes.id, quotes.quote, authors.author, categories.category
                FROM quotes
                JOIN authors ON authors.id = quotes.authorId
                JOIN categories ON categories.id = quotes.categoryId
                WHERE quotes.id = ?
                LIMIT 0, 1';

      $stmt = $this->conn->prepare($query);

      $stmt->bindParam(1, $this->id);

      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if($row && $row['quote']){
          $this->id = $row['id'];
          $this->quote = $row['quote'];
          $this->author = $row['author'];
          $this->category = $row['category'];
      }
  }

  public function create() {

    $query = 'SELECT * FROM authors WHERE id = :id LIMIT 1';
    $stmt = $this->conn->prepare($query);
    $this->authorId = htmlspecialchars(strip_tags($this->authorId));
    $stmt-> bindParam(':id', $this->authorId);
    $stmt->execute();
    if($stmt->rowCount() == 0){
      return -2;
    }
    $query = 'SELECT * FROM categories WHERE id = :id LIMIT 1';
    $stmt = $this->conn->prepare($query);   
    $this->categoryId = htmlspecialchars(strip_tags($this->categoryId)); 
    $stmt-> bindParam(':id', $this->categoryId);   
    $stmt->execute();
    if($stmt->rowCount() == 0){
      return -3;
    }

    $query = 'INSERT INTO 
                quotes (quote, authorId, categoryId) 
              VALUES 
                (:quote, :authorId, :categoryId)';

    $stmt = $this->conn->prepare($query);

    $this->quote = htmlspecialchars(strip_tags($this->quote));
    $this->authorId = htmlspecialchars(strip_tags($this->authorId));
    $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));

    $stmt-> bindParam(':quote', $this->quote);
    $stmt-> bindParam(':authorId', $this->authorId);
    $stmt-> bindParam(':categoryId', $this->categoryId);

    if($stmt->execute()) {
      return $this->conn->lastInsertId();
    }

    printf("Error: $s.\n", $stmt->error);

    return -1;
  }

  public function update() {

    $query = 'SELECT * FROM authors WHERE id = :aid LIMIT 1';
    $stmt = $this->conn->prepare($query);
    $this->authorId = htmlspecialchars(strip_tags($this->authorId));
    $stmt-> bindParam(':aid', $this->authorId);
    $stmt->execute();
    if($stmt->rowCount() == 0){
      return -2;
    }
    $query = 'SELECT * FROM categories WHERE id = :cid LIMIT 1';
    $stmt = $this->conn->prepare($query);   
    $this->categoryId = htmlspecialchars(strip_tags($this->categoryId)); 
    $stmt-> bindParam(':cid', $this->categoryId);   
    $stmt->execute();
    if($stmt->rowCount() == 0){
      return -3;
    }
    $query = 'SELECT * FROM quotes WHERE id = :qid LIMIT 1';
    $stmt = $this->conn->prepare($query);   
    $this->id = htmlspecialchars(strip_tags($this->id)); 
    $stmt-> bindParam(':qid', $this->id);   
    $stmt->execute();
    if($stmt->rowCount() == 0){
      return -4;
    }

    $query = 'UPDATE quotes 
              SET
                quote = :quote,
                authorId = :authorId,
                categoryId = :categoryId 
              WHERE
                id = :id';

  $stmt = $this->conn->prepare($query);

  $this->quote = htmlspecialchars(strip_tags($this->quote));
  $this->id = htmlspecialchars(strip_tags($this->id));

  $stmt-> bindParam(':quote', $this->quote);
  $stmt-> bindParam(':id', $this->id);
  $stmt-> bindParam(':authorId', $this->authorId);
  $stmt-> bindParam(':categoryId', $this->categoryId);

  if($stmt->execute()) {
    return $stmt->rowCount();
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
      //code...
      if($stmt->execute()) {
        return $stmt->rowCount();
      }
    } catch (\Throwable $th) {

    }
    return -1;
    }
  }
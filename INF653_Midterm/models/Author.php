<?php
    class Author{
        
        private $conn;
        private $table = 'authors';

        public $id;
        public $author;

        public function __construct($db){
            $this->conn = $db;
        }

        public function read(){
            $query = 'SELECT 
                        id,
                        author
                    FROM
                    ' . $this->table . '';

            $stmt = $this->conn->prepare($query);
 
            $stmt->execute();

            return $stmt;
        }

        public function read_single(){
            $query = 'SELECT 
                        id,
                        author
                    FROM
                ' . $this->table . '
                WHERE
                id = ?
            LIMIT 1';
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->id);

            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if(isset($row['id'])&& isset($row['author'])){
                $this->id = $row['id'];
                $this->author = $row['author'];
              }
        }

        public function create(){
            $query = 'INSERT INTO ' .
                    $this->table . '
                (
                author)
                VALUES
                    (
                    :author)
                RETURNING id, author';

            $stmt = $this->conn->prepare($query);

            $this->author = htmlspecialchars(strip_tags($this->author));

            $stmt->bindParam(':author', $this->author);

            if($stmt->execute()){
                return $stmt->fetch()["id"];
            }else{
                printf("Error: %s.\n", $stmt->error);
                return false;
            }
        }

        public function update(){
            $query = 'UPDATE ' .
            $this->table . '
        SET
            author = :author
            WHERE
                id = :id';
                

            $stmt = $this->conn->prepare($query);

            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->author = htmlspecialchars(strip_tags($this->author));

            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id', $this->id);
            
            // Execute query
            if($stmt->execute()){
                return true;
            }else{
                // Print error if something goes wrong.
                printf("Error: %s.\n", $stmt->error);
                return false;
            }
        }

        //Delete Author
        public function delete(){
            //Create query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            //Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':id', $this->id);

            // Execute query
            if($stmt->execute()){
                return true;
            }else{
                // Print error if something goes wrong.
                printf("Error: %s.\n", $stmt->error);
                return false;
            }
        }

}
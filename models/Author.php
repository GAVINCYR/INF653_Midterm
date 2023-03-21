<?php
    //category class
    class Author{
        private $conn;
        private $table = 'authors';

        public $id;
        public $author;

        //connection
        public function __construct($db) {
            $this->conn = $db;
        }

        //read function
        public function read(){
            //Create query
            $query = "SELECT
                        id,
                        author
                        FROM ".$this->table."
                        ORDER BY id ASC";
            
            //Prepare statement
            $stmt = $this->conn->prepare($query);

            //execute
            $stmt->execute();

            //return statement
            return $stmt;
        }

        public function read_single(){
            //Create query
            $query = "SELECT
                        id,
                        author
                        FROM " . $this->table ."
                        WHERE id = :id
                        LIMIT 1 OFFSET 0";
            
            //prepare statement
            $stmt= $this->conn->prepare($query);

            //Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));

            //bind data
            $stmt->bindParam(':id', $this->id);

            //execute
            $stmt->execute();

            //retrieve data
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            //Set the data
            if($row){
                $this->id = $row['id'];
                $this->author = $row['author'];
                return true;
            } else {
                return false;
            }
        }

        public function create() {
            //create a category
            $query = "INSERT INTO " . $this->table ." (author) VALUES (:author)";

            //prepare statement
            $stmt= $this->conn->prepare($query);

            //clean data
            $this->author = htmlspecialchars(strip_tags($this->author));
    
            //bind data
            $stmt->bindParam(':author', $this->author);

             //execute
            $stmt->execute();

            if($stmt->execute()){
                return true;
            } else {
                printf("Error: %s. \n", $stmt->error);
                return false;
            }
        }
    
       //Update Post
       public function update(){
        // Create query
        $query = 'UPDATE ' . $this->table . '
          SET
            author = :author
          WHERE
            id = :id';
        
        //PREPARE STMT
         $stmt = $this->conn->prepare($query);

         //clean DATA
         $this->author = htmlspecialchars(strip_tags($this->author));
         $this->id = htmlspecialchars(strip_tags($this->id));
        
         //Bind data
         $stmt->bindParam(':author', $this->author);
         $stmt->bindParam(':id', $this->id);

         //Execute query
         if($stmt->execute()){
            return true;
         }

         //print error if something goes wrong
         printf("Error: %s.\n", $stmt->error);

         return false;

    }

    //Delete post
    public function delete(){
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        //prepare statement
        $stmt = $this->conn->prepare($query);

        //clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        //bind data
        $stmt->bindParam(':id', $this->id);

        //Execute query
        if($stmt->execute()){
            return true;
         } else {

         //print error if something goes wrong
         printf("Error: %s.\n", $stmt->error);

         return false;
         }
    }
}
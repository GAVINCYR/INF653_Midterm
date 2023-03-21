<?php
    //category class
    class Category{
        private $conn;
        private $table = 'categories';

        public $id;
        public $category;

        //connection
        public function __construct($db) {
            $this->conn = $db;
        }

        //read function
        public function read(){
            //Create query
            $query = "SELECT
                        id,
                        category
                        FROM " . $this->table . "
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
                        category
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
                $this->category = $row['category'];
                return true;
            } else {
                return false;
            }
        }

        public function create() {
            //create a category
            $query = "INSERT INTO " . $this->table ." (category) VALUES (:category)";

            //prepare statement
            $stmt= $this->conn->prepare($query);

            //clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
    
            //bind data
            $stmt->bindParam(':category', $this->category);

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
            category = :category
          WHERE
            id = :id';
        
        //PREPARE STMT
         $stmt = $this->conn->prepare($query);

         //clean DATA
         $this->category = htmlspecialchars(strip_tags($this->category));
         $this->id = htmlspecialchars(strip_tags($this->id));
        
         //Bind data
         $stmt->bindParam(':category', $this->category);
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
<?php
    class Quote{
        //DB STUFF
        private $conn;
        private $table = 'quotes';

        //POST PROPERTIES
        public $id;
        public $category_id;
        public $author_id;
        public $author;
        public $category;
        public $quote;

        //Constructor with DB
        public function __construct($db){
            $this->conn = $db;
        }

        //GET POST
        public function read(){
            //create query
            if (isset($this->author_id) && isset($this->category_id)){
                $query = "SELECT
                        q.id,
                        q.quote,
                        a.author as author,
                        c.category as category
                        FROM " . $this->table . " q
                        INNER JOIN authors a on q.author_id = a.id
                        INNER JOIN categories c on q.category_id = c.id
                        WHERE a.id = :author_id AND c.id = :category_id";
            }
            else if (isset($this->author_id)){
                $query = "SELECT
                        q.id,
                        q.quote,
                        a.author as author,
                        c.category as category
                        FROM " . $this->table . " q
                        INNER JOIN authors a on q.author_id = a.id
                        INNER JOIN categories c on q.category_id = c.id
                       WHERE a.id = :author_id";
            }
            else if(isset($this->category_id)){
                $query = "SELECT
                        q.id,
                        q.quote,
                        a.author as author,
                        c.category as category
                        FROM " . $this->table . " q
                        INNER JOIN authors a on q.author_id = a.id
                        INNER JOIN categories c on q.category_id = c.id
                        WHERE c.id = :category_id";
            } else {
                $query = "SELECT
                        q.id,
                        q.quote,
                        a.author as author,
                        c.category as category
                        FROM " . $this->table . " q
                        INNER JOIN authors a on q.author_id = a.id
                        INNER JOIN categories c on q.category_id = c.id
                        ORDER BY q.id ASC";
            }
        
            //create a statement
            $stmt = $this->conn->prepare($query);
            if($this->author_id){
                $stmt->bindParam(':author_id', $this->author_id);
            }
            if($this->category_id){
                $stmt->bindParam(':category_id', $this->category_id);
            }

            //execute
            $stmt->execute();

            //return stmt
           return $stmt;

        }

        //Get single post
        public function read_single(){
            //create query
            $query = "SELECT
                     q.id,
                     q.quote,
                     a.author as author,
                     c.category as category
                     FROM " . $this->table . " q
                     INNER JOIN authors a on q.author_id = a.id
                     INNER JOIN categories c on q.category_id = c.id
                     WHERE q.id = :id
                     LIMIT 1 OFFSET 0";

            //prepare statement
            $stmt = $this->conn->prepare($query);

            //bind the param/id
            $stmt->bindParam(':id', $this->id);

            //execute statement
            $stmt->execute();

            //fetch data
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            //set properties if there is data set
            if($row){
                $this->id = $row['id'];
                $this->quote = $row['quote'];
                $this->category = $row['category'];
                $this->author = $row['author'];
                return true;
            } else{
                return false; //no data
            }
        }
        
        //Create Post
        public function create(){
            // Create query
            $query = 'INSERT INTO ' . $this->table . '
              SET
                quote = :quote,
                author_id = :author_id,
                category_id = :category_id';
            
            //PREPARE STMT
             $stmt = $this->conn->prepare($query);

             //clean DATA
             $this->quote = htmlspecialchars(strip_tags($this->quote));
             $this->author_id = htmlspecialchars(strip_tags($this->author_id));
             $this->category_id = htmlspecialchars(strip_tags($this->category_id));

             //Bind data
             $stmt->bindParam(':quote', $this->quote);
             $stmt->bindParam(':author_id', $this->author_id);
             $stmt->bindParam(':category_id', $this->category_id);

             //Execute query
             if($stmt->execute()){
                return true;
             }

             //print error if something goes wrong
             printf("Error: %s.\n", $stmt->error);

             return false;

        }

       //Update Post
        public function update(){
            // Create query
            $query = 'UPDATE ' . $this->table . '
              SET
                id = :id,
                quote = :quote,
                author_id = :author_id,
                category_id = :category_id
              WHERE
                id = :id';
            
            //PREPARE STMT
             $stmt = $this->conn->prepare($query);

             //clean DATA
             $this->id = htmlspecialchars(strip_tags($this->id));
             $this->quote = htmlspecialchars(strip_tags($this->quote));
             $this->author_id = htmlspecialchars(strip_tags($this->author_id));
             $this->category_id = htmlspecialchars(strip_tags($this->category_id));

             //Bind data
             $stmt->bindParam(':id', $this->id);
             $stmt->bindParam(':quote', $this->quote);
             $stmt->bindParam(':author_id', $this->author_id);
             $stmt->bindParam(':category_id', $this->category_id);

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
             }

             //print error if something goes wrong
             printf("Error: %s.\n", $stmt->error);

             return false;
        } 
    }
<?php
class Quote{
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    public function __construct($db){
        $this->conn = $db;
    }

    public function read(){
        $query = 'SELECT q.id, q.quote, 
        authors.author, authors.id AS author_id, 
        categories.category, categories.id AS category_id
        FROM ' . $this->table . ' q 
        INNER JOIN authors ON q.author_id = authors.id 
        INNER JOIN categories ON q.category_id = categories.id
        ORDER BY q.id asc';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function read_single(){
        $query = 'SELECT q.id, q.quote, 
        authors.author, 
        categories.category
        FROM ' . $this->table . ' q 
        INNER JOIN authors ON q.author_id = authors.id 
        INNER JOIN categories ON q.category_id = categories.id 
        WHERE q.id = ?';
    
        $stmt = $this->conn->prepare($query);

	    $stmt->bindParam(1, $this->id);

		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->quote = $row;
    }

    public function create(){
        $tempQuote = $this->quote;
        $tempAuthorId= $this->author_id;
        $tempCategoryId = $this->category_id;

        $query = 'SELECT authors.id, authors.author FROM authors WHERE authors.id = ?';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->author_id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->author_id = $row;

        if($this->author_id === false){
            echo json_encode(array('message' => 'author_id Not Found'));
            exit();
        }
        else{
            $this->quote = $tempQuote;
            $this->author_id = $tempAuthorId;
            $this->category_id = $tempCategoryId;

            $query = 'SELECT categories.id, categories.category FROM categories WHERE categories.id = ?';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->category_id);

            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->category_id = $row;
            
            if($this->category_id === false){
                echo json_encode(array('message' => 'category_id Not Found'));
                exit();
            }
            else{
                $this->quote = $tempQuote;
                $this->author_id = $tempAuthorId;
                $this->category_id = $tempCategoryId;

                $query = 'SELECT q.quote, q.id FROM ' . $this->table . ' q  WHERE q.quote = ?';

                $stmt = $this->conn->prepare($query);

                $stmt->bindParam(1, $this->quote);

                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->quote = $row;
            
                if($this->quote === false){
                    $this->quote = $tempQuote;
                    $this->author_id = $tempAuthorId;
                    $this->category_id = $tempCategoryId;

                    $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)';
            
                    $stmt = $this->conn->prepare($query);

                    $this->quote = htmlspecialchars(strip_tags($this->quote));
                    $this->author_id = htmlspecialchars(strip_tags($this->author_id));
                    $this->category_id = htmlspecialchars(strip_tags($this->category_id));
                    $stmt->bindParam(':quote', $this->quote);
                    $stmt->bindParam(':author_id', $this->author_id);
                    $stmt->bindParam(':category_id', $this->category_id);

                   
                    if($stmt->execute()){
                        $this->quote = $tempQuote; 
                        $this->author_id = $tempAuthorId;
                        $this->category_id = $tempCategoryId;

                        $query = 'SELECT quotes.id, quotes.quote, quotes.author_id, quotes.category_id FROM quotes WHERE quotes.quote = ?';

                        $stmt = $this->conn->prepare($query);

                        $stmt->bindParam(1, $this->quote);
                      
                        $stmt->execute();

                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        $this->quote = $row;

                        echo(json_encode($this->quote));

                        return true;
                    }
                    else{
                        return false;
                    }
                }
            }
        }
    }

	public function update()
	{
        $tempQuote = $this->quote;
        $tempAuthorId = $this->author_id;
        $tempCategoryId = $this->category_id;
        $tempId = $this->id;

        $query = 'SELECT authors.id FROM authors WHERE authors.id = ?';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->author_id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->author_id = $row;

        if($this->author_id === false){
            echo json_encode(array('message' => 'author_id Not Found'));
            exit();
        }
        else{
            $this->quote = $tempQuote; 
            $this->author_id = $tempAuthorId; 
            $this->category_id = $tempCategoryId; 
            $this->id = $tempId; 
                
            $query = 'SELECT categories.id FROM categories WHERE categories.id = ?';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->category_id);

            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->category_id = $row;
            
            if($this->category_id === false){
                echo json_encode(array('message' => 'category_id Not Found'));
                exit();
            }
            else{
                $this->quote = $tempQuote; 
                $this->author_id = $tempAuthorId; 
                $this->category_id = $tempCategoryId; 
                $this->id = $tempId; 
                
                $query = 'SELECT q.id FROM ' . $this->table . ' q  WHERE q.id = ?';

                $stmt = $this->conn->prepare($query);

                $stmt->bindParam(1, $this->id);

                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->id = $row;
        
                if($this->id === false){
                    echo json_encode(array('message' => 'No Quotes Found'));
                    exit();
                }
                else{
                    $this->quote = $tempQuote; 
                    $this->author_id = $tempAuthorId; 
                    $this->category_id = $tempCategoryId; 
                    $this->id = $tempId; 
                    
                    $query = 'UPDATE ' . $this->table . ' SET quote = :quote, author_id = :author_id, category_id = :category_id WHERE id = :id';

                    $stmt = $this->conn->prepare($query);

                    $this->quote = htmlspecialchars(strip_tags($this->quote));
                    $this->author_id = htmlspecialchars(strip_tags($this->author_id));
                    $this->category_id = htmlspecialchars(strip_tags($this->category_id));
                    $this->id = htmlspecialchars(strip_tags($this->id));

                    $stmt->bindParam(':quote', $this->quote);
                    $stmt->bindParam(':author_id', $this->author_id);
                    $stmt->bindParam(':category_id', $this->category_id);
                    $stmt->bindParam(':id', $this->id);

                    if($stmt->execute()){
                        $this->quote = $tempQuote; 
                        $this->author_id = $tempAuthorId; 
                        $this->category_id = $tempCategoryId; 
                        $this->id = $tempId; 

                        $query = 'SELECT quotes.id, quotes.quote, quotes.author_id, quotes.category_id FROM quotes WHERE quotes.quote = ?';

                        $stmt = $this->conn->prepare($query);

                        $stmt->bindParam(1, $this->quote);
                        
                        $stmt->execute();

                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        $this->quote = $row;

                        echo(json_encode($this->quote));
                        return true;
                    }
                    else{
                        printf("Error: %s.\n", $stmt->error);
                        return false;
                    }
                }
            }
        }
    }

	public function delete()
	{
        $temp = $this->id;

        $query = 'SELECT q.id FROM ' . $this->table . ' q  WHERE q.id = ?';

        $stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $this->id);

		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row;
       
        if($this->id === false){
            echo json_encode(array('message' => 'No Quotes Found'));
                exit();
        }
        else{
            $this->id = $temp;

            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            $stmt = $this->conn->prepare($query);

            $this->id = htmlspecialchars(strip_tags($this->id));

            $stmt->bindParam(':id', $this->id);
            
            if($stmt->execute()){
                $array = array('id' => $this->id);
                echo(json_encode($array));
                return true;
            }
            else{
                printf("Error: %s.\n", $stmt->error);
                return false;
            }
        }
	}
}

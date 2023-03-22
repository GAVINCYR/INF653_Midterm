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
        $query = 'SELECT a.id, a.author FROM ' . $this->table . ' a ORDER BY a.id asc';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function read_single(){
        $query = 'SELECT a.id, a.author FROM ' . $this->table . ' a WHERE a.id = ?';

        $stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $this->id);

		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->author = $row;
    }

    public function create(){
        $temp = $this->author;

        $query = 'SELECT a.id, a.author FROM ' . $this->table . ' a WHERE a.author = ?';

        $stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $this->author);

		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->author = $row;
       
        if($this->author === false){
            $this->author = $temp;

            $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author)';
            
            $stmt = $this->conn->prepare($query);

            $this->author = htmlspecialchars(strip_tags($this->author));
            $stmt->bindParam(':author', $this->author);

            if($stmt->execute()){
                $this->author = $temp;

                $query = 'SELECT authors.id, authors.author FROM authors WHERE authors.author = ?';

                $stmt = $this->conn->prepare($query);

                $stmt->bindParam(1, $this->author);

                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->author = $row;

                echo(json_encode($this->author));

                return true;
            }
            else{
                printf("Error: %s.\n", $stmt->error);
                return false;
            }
        }	 
        else{
            return false;
        }

    }

	public function update()
	{
		$temp = $this->author;

        $query = 'SELECT a.id FROM ' . $this->table . ' a WHERE a.id = ?';

        $stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $this->id);

		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->author = $row;
        if($this->author === false){
            echo json_encode(
                array('message' => 'author_id Not found'));
            exit();
        }
        else{
            $this->author = $temp;
            $query = 'UPDATE ' . $this->table . ' SET author = :author WHERE id = :id';

            $stmt = $this->conn->prepare($query);

            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));

            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id', $this->id);
            
            if($stmt->execute()){
                $this->author = $temp;

                $query = 'SELECT authors.id, authors.author FROM authors WHERE authors.author = ?';

                $stmt = $this->conn->prepare($query);

                $stmt->bindParam(1, $this->author);
              
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->author = $row;

                echo(json_encode($this->author));
                return true;
            }
            else{
                printf("Error: %s.\n", $stmt->error);
                return false;
            }
        }
    }

        public function delete()
        {
            $temp = $this->id;

            $query = 'SELECT a.id FROM ' . $this->table . ' a WHERE a.id = ?';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->id);

            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row;
            if($this->id === false){
                echo json_encode(
                    array('message' => 'author_id Not found'));
                exit();
            }
            else{
                $this->id = $temp;

                $query = 'DELETE FROM quotes WHERE author_id = :id';

                $stmt = $this->conn->prepare($query);

                $this->id = htmlspecialchars(strip_tags($this->id));

                $stmt->bindParam(':id', $this->id);
            
                if($stmt->execute()){
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
                else{
                    printf("Error: %s.\n", $stmt->error);
                    return false;
                }	
            }
        }
}
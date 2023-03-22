<?php
class Category{
    private $conn;
    private $table = 'categories';

    public $id;
    public $category;

    public function __construct($db){
        $this->conn = $db;
    }

    public function read(){
        $query = 'SELECT c.id, c.category FROM ' . $this->table . ' c ORDER BY c.id asc';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function read_single(){
        $query = 'SELECT c.id, c.category FROM ' . $this->table . ' c WHERE c.id = ?';
        $stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $this->id);

		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->category = $row;
    }

    public function create(){
        $temp = $this->category;

        $query = 'SELECT c.id, c.category FROM ' . $this->table . ' c WHERE c.category = ?';

        $stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $this->category);

		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->category = $row;
       
        if($this->category === false){
            $this->category = $temp;

            $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category)';
            
            $stmt = $this->conn->prepare($query);

            $this->category = htmlspecialchars(strip_tags($this->category));
            $stmt->bindParam(':category', $this->category);

            if($stmt->execute()){
                $this->category = $temp;

                $query = 'SELECT categories.id, categories.category FROM categories WHERE categories.category = ?';

                $stmt = $this->conn->prepare($query);

                $stmt->bindParam(1, $this->category);

                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->category = $row;

                echo(json_encode($this->category));

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
        $temp = $this->category;

        $query = 'SELECT c.id FROM ' . $this->table . ' c WHERE c.id = ?';

        $stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $this->id);

		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->category = $row;
        if($this->category === false){ 
            echo json_encode(
                array('message' => 'category_id Not found'));
            exit();
        }
        else{
            $this->category = $temp;
          
            $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id';

            $stmt = $this->conn->prepare($query);

            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));

            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':id', $this->id);
            
            if($stmt->execute()){
                $this->category = $temp;

                $query = 'SELECT categories.id, categories.category FROM categories WHERE categories.category = ?';

                $stmt = $this->conn->prepare($query);

                $stmt->bindParam(1, $this->category);

                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->category = $row;

                echo(json_encode($this->category));
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

        $query = 'SELECT c.id FROM ' . $this->table . ' c WHERE c.id = ?';

        $stmt = $this->conn->prepare($query);

		$stmt->bindParam(1, $this->id);

		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row;
    
        if($this->id === false){
            echo json_encode(
                array('message' => 'category_id Not found'));
            exit();
        }
        else{
            $this->id = $temp;

		    $query = 'DELETE FROM quotes WHERE category_id = :id';

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
<?php
class Model
{
    protected $conn;
    
    function __construct()
    {
       try {
           $this->conn = new PDO("mysql:host=localhost;dbname=".DB_NAME.";charset=UTF8;SET time_zone = 'Asia/Ho_Chi_Minh'","root","");
           $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       } catch (PDOException $e) {
           echo "Connection failed: " . $e->getMessage();
       } 
    }

    function insert($table, $value, $row = null){
        if($row != null){$row = rtrim(implode(',',$row),',');}
        $value = rtrim(implode('\',\'',$value),',');
        $value = str_pad($value, strlen($value)+2,'\'',STR_PAD_BOTH);
        $sql = "INSERT INTO ". $table . " (".$row.") VALUES (".$value.")";
        $stmt = $this->conn->prepare($sql);
        try {
            if($stmt->execute()){
                return 1;
            }
        } catch (PDOException $e) {
            echo "Error ".$e->getCode();
            return 0;
        }
    }

    function update($table, $setRow, $setVal, $cond)
    {
        $sql = '';
        if(gettype($setRow) == 'string'){
            $sql = "UPDATE ". $table . "SET ". $setRow . "='".$setVal."' WHERE ".$cond."";
        } else{
            $set = '';
            for ($i=0; $i < count($setRow); $i++) { 
                $set .= $setRow[$i].'='.'\''.$setVal[$i].'\',';
            }
            $set = rtrim($set,',');
            $sql = "UPDATE ".$table." SET ".$set." WHERE ".$cond."";
        }
        $stmt = $this->conn->prepare($sql);
        try {
            if($stmt->execute()){
                return 1;
            }
        } catch (PDOException $e) {
            echo "Error: ".$e->getCode()->getMessage();
            return 0;
        }
    }

    function delete($table, $cond){
        $sql = "DELETE FROM ".$table." WHERE ".$cond."";
        $stmt = $this->conn->prepare($sql);
        try {
            if($stmt->execute())
            {
                return 1;
            }
        } catch (PDOException $e) {
            echo "Error: ".$e->getCode();
            return 0;
        }
    }

    function __destruct()
    {
        $this->conn = null;
    }
}
?>

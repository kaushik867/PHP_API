<?php


class dbConnection extends PDO{

    private $host='localhost';
    private $user='root';
    private $password='';
    private $database='user';
    private $data_inserted = array(
        'success' => true,
        'message' => 'data inserted successfully',
        'status_code'=>200
    );
    private $data_deleted = array(
        'success'=>true,
        'message'=>'data deleted successfully',
        'status_code'=>200
    );

    private $data_updated = array(
        'success'=>true,
        'message'=>'data updated successfully',
        'status_code'=>200
    );

    function __construct()
    {
       $this->dbcon = parent::__construct("mysql:host=$this->host; dbname=$this->database",$this->user,$this->password);
    }
   
    function fetch_all(dbConnection $con){
        $sql = "SELECT * FROM users_table";
        $stmt = $con->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function fetch( dbConnection $con ,$args ){
        $id = $args['id'];
        $sql = "SELECT * FROM users_table WHERE id= :id";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function add_data(dbConnection $con , $postArr){
        if(count($postArr) == 3){
            $sql = "INSERT INTO users_table(name, email, phone) VALUES(:name,:email,:phone)";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':name',$postArr['name']);
            $stmt->bindParam(':email',$postArr['email']);
            $stmt->bindParam(':phone',$postArr['phone']);
            $stmt->execute();
            
            return $this->data_inserted;
        }    
    }

    function del_data(dbConnection $con, $args){
        $sql = "DELETE FROM users_table WHERE id = :id";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(':id',$args['id']);
        if($stmt->execute()){
            return $this->data_deleted;
        }
        
    }

    function update_data(dbConnection $con, $args, $putArr){
        if(count($putArr) == 3){
            $sql = "UPDATE users_table SET name= :name , email = :email, phone=:phone WHERE id=:id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':name', $putArr['name']);
            $stmt->bindParam(':email', $putArr['email']);
            $stmt->bindParam(':phone', $putArr['phone']);
            $stmt->bindParam(':id', $args['id']);
            $stmt->execute();
            return $this->data_updated;
        }
        
    }
  

}
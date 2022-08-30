<?php

class TaskGateway
{

    private $conn;
    public $id;
    public $name;
    public $priorty;
    public $is_completed;


    public function __construct($database)
    {
        $this->conn = $database->getConnection();
    }
    public function createTask(){
        $sql = "INSERT INTO task(name,priorty,is_completed) 
        VALUES(:name,:priorty,:is_completed)";

        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':name', $this->name);

        $stmt->bindParam(':priorty', $this->priorty);
        
        $stmt->bindParam(':is_completed', $this->is_completed);
        
        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function getAllTasks()
    {
        $sql = "SELECT * FROM task ORDER BY name";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute();

        $data = [];
        while ($row = $stmt->fetch()) {

            $row['is_completed'] = (bool) $row['is_completed'];
            $data[] = $row;
        }

        return $data;
    }

    public function getTask()
    {
        $sql = "SELECT * FROM task WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $data = $stmt->fetch();
        
        if ($data !== false) {
            $data['is_completed'] = (bool) $data['is_completed'];
            return $data;
        } else {

            http_response_code(404);
            echo json_encode([
                "msg" => "Task With Given ID Not Found",
            ]);
            exit;
            
        }
    }

    public function updateTask(){
        $sql = "UPDATE task SET
        name = :name,
        priorty = :priorty,
        is_completed = :is_completed
        WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':priorty', $this->priorty);
        $stmt->bindParam(':is_completed', $this->is_completed);
        $stmt->execute();

        return $this->getTask();
    }
    
    public function deleteTask(){
        $sql = "DELETE FROM Task WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id',$this->id);
        $stmt->execute();
    }






}

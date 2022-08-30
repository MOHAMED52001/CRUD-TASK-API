<?php


class TaskController
{

    public function __construct(private $taskGateway)
    {
    }

    public function proccess_Request($method, $id)
    {
        header("Content-Type: application/json");
        if ($id == null) {
            if ($method == 'POST') {
                $data = (array) json_decode(file_get_contents("php://input"), true);
                if (!empty($data)) {

                    $this->taskGateway->name = $data['name'];
                    $this->taskGateway->priorty = $data['priorty'];
                    $this->taskGateway->is_completed = $data['is_completed'];
                    $last_id = $this->taskGateway->createTask();
                    http_response_code(201);
                    echo json_encode([
                        "msg" => "Task created successfully with ID: " . $last_id
                    ]);
                } else {
                    echo json_encode([
                        "msg" => "Enter Task Body"
                    ]);
                }
            } elseif ($method == 'GET') {
                echo json_encode($this->taskGateway->getAllTasks());
            } else {
                http_response_code(405);
                header("Allow: POST , GET");
            }
        } else {
            switch ($method) {
                case 'GET':
                    $this->taskGateway->id = $id;
                    echo json_encode($this->taskGateway->getTask());
                break;

                case 'PATCH':
                    $this->taskGateway->id = $id;
                    $data = (array) json_decode(file_get_contents("php://input"), true);
                    if (!empty($data)) {

                        $this->taskGateway->name = $data['name'];
                        $this->taskGateway->priorty = $data['priorty'];
                        $this->taskGateway->is_completed = $data['is_completed'];
                        $updatedTask = $this->taskGateway->updateTask();
                        http_response_code(200);
                        echo json_encode([
                            "msg" => "Task updated successfully.",
                            "updatedTask" => $updatedTask
                        ]);
                    } else {
                        echo json_encode([
                            "msg" => "Enter Task Body"
                        ]);
                    }

                break;

                case 'DELETE':
                    $this->taskGateway->id = $id;
                    $this->taskGateway->deleteTask();
                    echo json_encode([
                        "msg" => "Task with id '$id' deleted successfully."
                    ]);
                break;

                default:
                    echo json_encode([
                        "msg" => "Unsupported Request Method",
                    ]);
                break;
            }
        }
    }
}

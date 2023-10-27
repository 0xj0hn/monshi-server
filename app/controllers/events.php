<?php
class Events extends Controller {
    public function add_event() {

    }

    public function remove_event() {
        $isValidated = Validator::validateElements($_POST, [
            "username",
            "password",
            "id"
        ]);
        $result = [];
        if ($isValidated) {
            $model = $this->model("event");
            $eventId = $_POST["id"];
            $isRemoved = $model->removeEvent($eventId);
            if ($isRemoved) {
                $result = [
                    "status" => "success",
                    "message" => "the event was removed"
                ];
            }else{
                $result = [
                    "code" => 404,
                    "status" => "error",
                    "message" => "an error occured while removing the event"
                ];
            }
        }else{
            $result = [
                "code" => 400,
                "status" => "error",
                "message" => "validation failed"
            ];
        }
        $this->view("json", $result);
    }

    public function get_events() {
        $isValidated = Validator::validateElements($_POST, [
            "username",
            "password",
            "secretary_id",
            "manager_id"
        ]);
        $result = [];
        if ($isValidated) {
            $model = $this->model("event");
            $authModel = $this->model("auth");
            $username = $_POST["username"];
            $password = $_POST["password"];
            $managerId = $_POST["manager_id"];
            $secretaryId = $_POST["secretary_id"];
            $isSecretaryAuth = $authModel->secretaryLogin($username, $password);
            $isManagerAuth = $authModel->managerLogin($username, $password);
            $isAuth = ($isSecretaryAuth || $isManagerAuth);
            if ($isAuth) {
                $events = $model->getEvents($managerId, $secretaryId);
                if ($events) {
                    $result = [
                        "status" => "success",
                        "message" => $events,
                    ];
                }else{
                    $result = [
                        "code" => 204,
                        "status" => "noelement",
                    ];
                }
            }
        }else{
            $result = [
                "code" => 400,
                "status" => "bad request",
                "message" => "you haven't provided needed elements"
            ];
        }
        $this->view("json", $result);
    }

    public function get_event() {

    }
}

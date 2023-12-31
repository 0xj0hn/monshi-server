<?php
class Auth extends Controller {
    public function secretary_login() {
        $model = $this->model("auth");
        $result = [];
        $isValidated = Validator::validateElements($_GET, [
            "username",
            "password"
        ]);
        if ($isValidated) {
            $username = $_GET["username"];
            $password = $_GET["password"];
            $isLogined = $model->secretaryLogin($username, $password);
            if ($isLogined) {
                $result = [
                    "status" => "success",
                ];
            }else{
                $result = [
                    "code" => 401,
                    "status" => "failed",
                ];
            }
        }else{
            $result = [
                "code" => 400,
                "status" => "error",
                "message" => "you haven't provided needed params"
            ];
        }
        $this->view("json", $result);
    }

    public function manager_login() {
        $model = $this->model("auth");
        $result = [];
        $isValidated = Validator::validateElements($_GET, [
            "username",
            "password"
        ]);
        if ($isValidated) {
            $username = $_GET["username"];
            $password = $_GET["password"];
            $isLogined = $model->managerLogin($username, $password);
            if ($isLogined) {
                $result = [
                    "status" => "success",
                ];
            }else{
                $result = [
                    "code" => 401,
                    "status" => "failed",
                ];
            }
        }else{
            $result = [
                "code" => 400,
                "status" => "error",
                "message" => "you haven't provided needed params"
            ];
        }
        $this->view("json", $result);
    }


}

<?php
class Auth extends Controller {
    public function secretary_signup() {
        $model = $this->model("auth");
        $result = [];
        $isSignedUp = $model->secretarySignup($_POST);
        if ($isSignedUp) {
            $result = [
                "status" => "success",
                "message" => "signup was successful"
            ];
        }else{
            $result = [
                "code" => 401,
                "status" => "error",
                "message" => "a problem has occured"
            ];
        }
        $this->view("json", $result);
    }


    public function manager_signup() {
        $result = [];
        $isSignedUp = $model->secretarySignup($_POST);
        if ($isSignedUp) {
            $model = $this->model("auth");
            $result = [
                "status" => "success",
                "message" => "signup was successful"
            ];
        }else{
            $result = [
                "code" => "401",
                "status" => "error",
                "message" => "a problem has occured"
            ];
        }
        $this->view("json", $result);
    }

    public function secretary_login() {
        $result = [];
        $isValidated = Validator::validateElements($_POST, [
            "username",
            "password"
        ]);
        if ($isValidated) {
            $model = $this->model("auth");
            $username = $_POST["username"];
            $password = $_POST["password"];
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
        $result = [];
        $isValidated = Validator::validateElements($_GET, [
            "username",
            "password"
        ]);
        if ($isValidated) {
            $model = $this->model("auth");
            $username = $_POST["username"];
            $password = $_POST["password"];
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

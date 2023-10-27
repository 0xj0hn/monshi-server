<?php
class Auth extends Controller {
    public function secretary_signup() {
        $model = $this->model("auth");
        $result = $model->secretarySignup($_POST);
        $this->view("json", $result);
    }


    public function manager_signup() {
        $model = $this->model("auth");
        $result = $model->managerSignup($_POST);
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
                    "access_token" => $model->generateJWTToken($username, 60 * 60),
                    "refresh_token" => $model->generateJWTToken($username) //I should implement the refresh token later.
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
                $jwtModel = $this->model("jwt");
                $result = [
                    "status" => "success",
                    "access_token" => $jwtModel->generateJWTToken($username, 60 * 60),
                    "refresh_token" => $jwtModel->generateJWTToken($username)
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

    public function refresh_token() {
        $result = [];
        $model = $this->model("jwt");
        $jwtToken = $model->getJWTToken();
        if ($jwtToken) {
            $model = $this->model("auth");
            $checkedToken = $model->checkJWTToken($jwtToken);
            if ($checkedToken) {
                $username = $checkedToken;
                $result = [
                    "status" => "success",
                    "access_token" => $model->generateJWTToken($username, 60 * 60),
                    "refresh_token" => $model->generateJWTToken($username)
                ];
            }else{
                $result = [
                    "status" => "error",
                    "message" => "your jwt signature isn't correct"
                ];
            }
        }else{
            $result = [
                "status" => "error",
                "jwt" => "you didn't provide jwt token"
            ];
        }
        $this->view("json", $result);
    }


}

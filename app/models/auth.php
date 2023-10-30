<?php
class AuthModel extends Model {
    private $secretSalt = "MoNshI@SecR3tS41t";
    //The below two functions are duplicated. Should be fixed later.
    public function secretaryLogin($username, $password) {
        $secretSalt = $this->secretSalt;
        $password = md5($secretSalt . $password . $secretSalt);
        $sql = "SELECT username, password FROM secretaries WHERE username = ? AND password = ?";
        $query = $this->query($sql, "ss", $username, $password);
        $result = $query->get_result();
        return $result->num_rows > 0;
    }

    public function managerLogin($username, $password) {
        $secretSalt = $this->secretSalt;
        $password = md5($secretSalt . $password . $secretSalt);
        $sql = "SELECT username, password FROM managers WHERE username = ? AND password = ?";
        $query = $this->query($sql, "ss", $username, $password);
        $result = $query->get_result();
        return $result->num_rows > 0;
    }

    public function secretarySignup($data) {
        $result = [];
        $isValidated = Validator::validateElements($data, [
            "username",
            "password",
            "name",
            "family",
            "phone_number"
        ]);
        if ($isValidated) {
            $username = $data["username"];
            $secretSalt = $this->secretSalt;
            $password = $data["password"];
            $password = md5($secretSalt . $password . $secretSalt);
            $name = $data["name"];
            $family = $data["family"];
            $phoneNumber = $data["phone_number"];
            $sql = "INSERT INTO secretaries(username, password, name, family, phone_number) VALUES(?, ?, ?, ?, ?)";
            try{
                $this->query($sql, "sssss", $username, $password, $name, $family, $phoneNumber);
                require_once "app/models/jwt.php";
                $jwtModel = new JwtModel;
                $result = [
                    "status" => "success",
                    "message" => "signup was successful",
                    "access_token" => $jwtModel->generateJWTToken($username, 60 * 60), //Add 60 * 60 = 3600 seconds time limit for token
                    "refresh_token" => $jwtModel->generateJWTToken($username),
                ];
            }catch(\Exception $_) {
                $result = [
                    "code" => 401,
                    "status" => "error",
                    "message" => "a problem has occured"
                ];
            }
        }
        return $result;
    }

    public function managerSignup($data) {
        $result = [];
        $isValidated = Validator::validateElements($data, [
            "username",
            "password",
            "name",
            "family",
            "phone_number"
        ]);
        if ($isValidated) {
            $username = $data["username"];
            $password = md5($data["password"]);
            $name = $data["name"];
            $family = $data["family"];
            $phoneNumber = $data["phone_number"];
            $sql = "INSERT INTO managers(username, password, name, family, phone_number) VALUES(?, ?, ?, ?, ?)";
            try{
                $this->query($sql, "sssss", $username, $password, $name, $family, $phoneNumber);
                $jwtModel = new JwtModel;
                $result = [
                    "status" => "success",
                    "message" => "signup was successful",
                    "access_token" => $jwtModel->generateJWTToken($username, 60 * 60), //Add 60 * 60 = 3600 seconds time limit for token
                    "refresh_token" => $jwtModel->generateJWTToken($username),
                ];
            }catch(\Exception $e) {
                $result = [
                    "code" => 401,
                    "status" => "error",
                    "message" => "a problem has occured"
                ];

            }
        }
        return $result;
    }
}

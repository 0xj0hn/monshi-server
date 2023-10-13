<?php
class AuthModel extends Model {
    private $secretKey = "MoNSHISeCrEt@^%!&^$";
    public function setSecretKey($newSecretKey) {
        $this->secretKey = $newSecretKey;
    }

    public function generateJWTToken($username, $seconds=0) {
        $header = [
            "typ" => "JWT",
            "alg" => "HS256"
        ];
        $payload = [
            "usr" => $username,
            "iss" => "monshi",
            "sub" => "auth",
            "iat" => time()
        ];
        if ($seconds > 0) {
            $payload["exp"] = time() + $seconds;
        }
        $encodedHeader = $this->base64UrlEncode(json_encode($header));
        $encodedPayload = $this->base64UrlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->secretKey, true);
        $encodedSignature = $this->base64UrlEncode($signature);
        $jwt = $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;
        return $jwt;
    }

    private function base64UrlEncode($value) {
        $base64Encoded = base64_encode($value);
        $base64UrlEncoded = strtr($base64Encoded, '+/', '-_');
        $trimmedBase64UrlEncoded = rtrim($base64UrlEncoded, '=');
        return $trimmedBase64UrlEncoded;
    }

    //The below two functions are duplicated. Should be fixed later.
    public function secretaryLogin($username, $password) {
        $sql = "SELECT username, password FROM secretaries WHERE username = ? AND password = ?";
        $query = $this->query($sql, "ss", $username, $password);
        $result = $query->get_result();
        return $result->num_rows > 0;
    }

    public function managerLogin($username, $password) {
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
            $password = md5($data["password"]);
            $name = $data["name"];
            $family = $data["family"];
            $phoneNumber = $data["phone_number"];
            $sql = "INSERT INTO secretaries(username, password, name, family, phone_number) VALUES(?, ?, ?, ?, ?)";
            $query = $this->query($sql, "sssss", $username, $password, $name, $family, $phoneNumber);
            if ($query) {
                $result = [
                    "status" => "success",
                    "message" => "signup was successful",
                    "access_token" => $this->generateJWTToken($username, 60 * 60), //Add 60 * 60 = 3600 seconds time limit for token
                    "refresh_token" => $this->generateJWTToken($username),
                ];
            }else{
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
            $query = $this->query($sql, "sssss", $username, $password, $name, $family, $phoneNumber);
            if ($query) {
                $result = [
                    "status" => "success",
                    "message" => "signup was successful",
                    "access_token" => $this->generateJWTToken($username, 60 * 60), //Add 60 * 60 = 3600 seconds time limit for token
                    "refresh_token" => $this->generateJWTToken($username),
                ];
            }else{
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

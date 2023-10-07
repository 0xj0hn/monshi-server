<?php
class AuthModel extends Model {
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
            return $query;
        }
        return false;
    }

    public function managerSignup($data) {
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
            return $query;
        }
        return false;
    }
}

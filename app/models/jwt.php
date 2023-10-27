<?php
class JwtModel extends Model {
    private $secretKey = "MoNSHISeCrEt@^%!&^$";
    public function getJWTToken() {
        $headers = getallheaders();
        if (array_key_exists("Authorization", $headers)) {
            $bearerSeparator = explode("Bearer ", $headers["Authorization"]);
            $jwtToken = $bearerSeparator[1];
            return $jwtToken;
        }
        return false;
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
        $signature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->secretKey);
        $encodedSignature = $this->base64UrlEncode($signature);
        $jwt = $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;
        return $jwt;
    }

    public function checkJWTToken($jwtToken) {
        $jwtSeparated = explode(".", $jwtToken);
        if (count($jwtSeparated) < 3) return false;
        $encodedHeader = $jwtSeparated[0];
        $encodedPayload = $jwtSeparated[1];
        $encodedSignature = $jwtSeparated[2];
        $givenTokenSignature = $this->base64UrlDecode($encodedSignature);
        $theExactSignature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->secretKey);
        $payload = $this->base64UrlDecode($encodedPayload);
        $payload = json_decode($payload, true);
        return $givenTokenSignature == $theExactSignature
            ? $payload["usr"] //username
            : false;
    }

    private function base64UrlEncode($value) {
        $base64Encoded = base64_encode($value);
        $base64UrlEncoded = strtr($base64Encoded, '+/', '-_');
        $trimmedBase64UrlEncoded = rtrim($base64UrlEncoded, '=');
        return $trimmedBase64UrlEncoded;
    }

    private function base64UrlDecode($encodedValue) {
        $base64UrlDecoded = strtr($encodedValue, '-_', '+/');
        $base64Decoded = base64_decode($base64UrlDecoded);
        return $base64Decoded;
    }
}
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
        $signature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->secretKey, true);
        $encodedSignature = $this->base64UrlEncode($signature);
        $jwt = $encodedHeader . '.' . $encodedPayload . '.' . $encodedSignature;
        return $jwt;
    }

    public function checkJWTToken($jwtToken) {
        $jwtSeparated = explode(".", $jwtToken);
        $encodedHeader = $jwtSeparated[0];
        $encodedPayload = $jwtSeparated[1];
        $encodedSignature = $jwtSeparated[2];
        $givenTokenSignature = json_decode($encodedSignature, true);
        $theExactSignature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->secretKey);
        $payload = json_decode($encodedPayload, true);
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
}

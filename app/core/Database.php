<?php 


class Database {
    protected $mysql;
    public $dbinformation;
    private $encryptionMethod = "...";
    private $encryptionKey = "..."; //32 bytes
    private $encryptionIV = "..."; //16 bytes
    public function __construct(){
        $host = "...";
        $username = "...";
        $password = "...";
        $dbname = "...";
        $this->mysql = new mysqli($host, $username, $password, $dbname);
        $this->mysql->query("set names utf8");
        if ($this->mysql->connect_error){
            die("ERROR: " . $this->mysql->connect_error);
        }
    }

    /*
     * @param $sql string the sql command to execute.
     * @param $types string the variable types of the command to execute. like "ssi" (string, string, int)
     * @param $values array() an array of values belongs to sql command.
     * @return return the response of query execution.
     */
    public function query($sql, $types=null, ...$values){
        $query = $this->mysql->prepare($sql);
        //bind parameters only when $types is not null.
        if ($types != null){
            $query->bind_param($types, ...$values ?? null);
        }
        $query->execute();
        return $query;
    }

    protected function encrypt($plainText){
        $cipherText = openssl_encrypt(
            $plainText,
            $this->encryptionMethod,
            $this->encryptionKey,
            0,
            $this->encryptionIV
        );
        return $cipherText;
    }

    protected function decrypt($cipherText){
        $plainText = openssl_decrypt(
            $cipherText,
            $this->encryptionMethod,
            $this->encryptionKey,
            0,
            $this->encryptionIV
        );
        return $plainText;
    }


}




?>

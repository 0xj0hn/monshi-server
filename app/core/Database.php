<?php 


class Database {
    protected $mysql;
    public $dbinformation;
    private $encryptionMethod = "AES-256-CBC";
    private $encryptionKey = "..."; //32 bytes
    private $encryptionIV = "..."; //16 bytes
    public function __construct(){
        $host = "localhost";
        $username = "root";
        $password = "Mhdmhdmhd82@#";
        $dbname = "db_monshi";
        $this->mysql = new mysqli($host, $username, $password, $dbname);
        $this->mysql->query("set names utf8");
        if ($this->mysql->connect_error){
            die("ERROR: " . $this->mysql->connect_error);
        }
        $this->generateTables();
    }

    public function generateTables() {
        $isSuccessful = (
            $this->createSecretariesTable() &&
            $this->createManagersTable() &&
            $this->createEventsTable()
        );
        return $isSuccessful;
    }

    public function createSecretariesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS `secretaries` (
            id int AUTO_INCREMENT PRIMARY KEY,
            username varchar(50) UNIQUE ,
            password varchar(50),
            name varchar(50),
            family varchar(50),
            phone_number varchar(11),
            firebase_token text,
            manager_id int
        )";
        return $this->mysql->query($sql);
    }

    public function createManagersTable() {
        $sql = "CREATE TABLE IF NOT EXISTS `managers` (
            id int AUTO_INCREMENT PRIMARY KEY,
            username varchar(50) UNIQUE,
            password varchar(50),
            name varchar(50),
            family varchar(50),
            phone_number varchar(11),
            firebase_token text
        )";
        return $this->mysql->query($sql);
    }

    public function createEventsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS `events` (
            id int AUTO_INCREMENT PRIMARY KEY,
            title varchar(50),
            body varchar(255),
            created_at varchar(50),
            started_at varchar(50),
            completed_at varchar(50),
            notif_at varchar(50),
            manager_id int
        )";
        return $this->mysql->query($sql);
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

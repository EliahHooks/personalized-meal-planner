<?php

class db {
//connect to DB
    public $error = " ";
    private $pdo = null;
    private $stmt = null;
    
    function __construct() {
    try {
        $this->pdo = new PDO (
            "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=".DB_CHARSET,
            DB_USER, DB_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        
    } catch (PDOException $e) {
        echo "ERROR connecting <br>".$e->getMessage();
        return null;
    }     
}

public function selectOne($sql, $params = []) {
    try {
        $this->stmt = $this->pdo->prepare($sql);
        $this->stmt->execute($params);
        return $this->stmt->fetch(); // fetches one row
    } catch (PDOException $e) {
        echo "SelectOne Error: " . $e->getMessage();
        return null;
    }
}

// close connection
      function __destruct() {
          if ($this->stmt !== null) {$this->stmt = null;}
          if ($this->pdo !== null) {$this->pdo =null;}
      }
      
//select data all
      function select ($sql, $data=null) {
          try{
              $this->stmt = $this->pdo->prepare($sql);
              $this->stmt->execute($data);
              return $this->stmt->fetchAll();
          } catch (PDOException $e) {
               echo $sql . "<br>".$e->getMessage();
               return null;
          }
      }
      
      public function insert($sql, $params = []) {
    try {
        $this->stmt = $this->pdo->prepare($sql);
        return $this->stmt->execute($params);
    } catch (PDOException $e) {
        echo "Insert Error: " . $e->getMessage();
        return false;
    }
}

  
}



//define DB settings
define("DB_HOST", "localhost");
define("DB_NAME", "mypmp");
define("DB_CHARSET", "utf8mb4");
define("DB_USER", "root");
define("DB_PASSWORD", "");

//define new DB object
$_db = new db();
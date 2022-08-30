<?php

class Database{
    
    public function __construct(
        private $host,
        private $db_name,
        private $user,
        private $passwd
    ){}

    public function getConnection(){

        $dsn = "mysql:host={$this->host};dbname={$this->db_name}";

        return new PDO($dsn, $this->user, $this->passwd,[
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false
        ]);

        
    }


}

?>
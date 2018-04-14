<?php

class DBConn{
	const DB_HOST = "localhost";
	const DB_USERNAME = "root";
	const DB_PASS = "";
	const DB_DEFAULT = "billing";
	const DB_PORT = 3306;

	public $db;
	private static $instance;

	private function __construct(){
		$dsn = 'mysql:host=' . self::DB_HOST.
               ';dbname='    . self::DB_DEFAULT.
               ';port='      . self::DB_PORT .
               ';connect_timeout=15';
        $user = self::DB_USERNAME;
        
        $password = self::DB_PASS;

        $this->db = new PDO($dsn, $user, $password);
        
	}

	public static function getInstance(){
		if(!isset(self::$instance)){
			$object = __CLASS__;
			self::$instance = new $object;
		}
		return self::$instance;
	}
}
?>
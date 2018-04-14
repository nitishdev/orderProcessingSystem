<?php
class RedisConn{

	private static $instance;
	private static $redisConf = array(
		'scheme'   => 'tcp',	
		'host'     => '127.0.0.1',
		'port'     => 6379,
		'persistent' => true
	);

	public function __construct(){
		try{
			$this->client = new Predis\Client(self::$redisConf);
		}
		catch(Exception $ex){
			$this->client = NULL;
			throw new Exception($ex);
		}
	}

	public static function getInstance(){
		if(!isset(self::$instance)){
			$class = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}

	public function set($key,$value,$ttl = 3600){
		try{
			if($this->client){
				$value = serialize($value);
				$key = (string)$key;
				$this->client->setEx($key,$ttl,$value);
			}
		}
		catch(Exception $ex){
			throw new Exception($ex);
		}
	}

	public function setHashObject($key,$arrVal,$ttl = 3600){
		try{
			$this->client->hmset($key,$arrVal);
			$this->client->expire($key,$ttl);
		}
		catch(Exception $ex){
			throw new Exception($ex);
		}
	}
}
?>
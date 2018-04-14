<?php
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
class Producer{
	private $connection;
	private $channel;
	private static $rabbitConf = array(
		'HOST'=>'localhost',
		'PORT'=>'5672',
		'USER'=>'guest',
		'PASS'=>'guest',
		'VHOST'=>'/'
	);

	public function __construct(){
		try{
			$this->connection = new AMQPConnection(self::$rabbitConf['HOST'], self::$rabbitConf['PORT'], self::$rabbitConf['USER'], self::$rabbitConf['PASS'], self::$rabbitConf['VHOST']);
		}
		catch(Exception $ex){
			throw new Exception($ex);
		}
		try{
			$this->channel = $this->connection->channel();
			$this->channel->setBodySizeLimit(NULL);
		} catch (Exception $ex) {
			throw new Exception($ex);
		}
	}

	public function sendMessage($msgData){
		$data = json_encode($msgData);
		$msg = new AMQPMessage($data, array('delivery_mode' => 2));
		$process = $msgData['process'];
		try{
			switch($process){
				case 'MAIL':
					$this->channel->queue_declare("sendMailQueue", false, true, false,false);
					$this->channel->basic_publish($msg, "", "sendMailQueue",true, false);
					break;
				case 'SMS':
					$this->channel->queue_declare("sendSMSQueue", false, true, false,false);
					$this->channel->basic_publish($msg, "", "sendSMSQueue",true, false);
					break;
				case 'INVOICE':
					$this->channel->queue_declare("prepareInvoiceQueue", false, true, false,false);
					$this->channel->basic_publish($msg, "", "prepareInvoiceQueue",true, false);
					break;
			}
		}
		catch(Exception $ex){
			throw new Exception($ex);
		}
	}
}
?>
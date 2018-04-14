<?php

class OrderProcessing{

	private $orderId;

	public function __construct($orderId){
		if(!isset($orderId)){
			throw new Exception("Blank orderId passed");
		}
		$this->orderId = $orderId;
	}

	public function doOrderProcessing(){
		$orderDetails = $this->getOrderDetails();

		$this->setOrderDetailsInCache($orderDetails);

		$msgBody = array("ORDERID"=>$this->orderId);

		$this->addOrderDetailsToQueues("MAIL",$msgBody);
		$this->addOrderDetailsToQueues("SMS",$msgBody);
		$this->addOrderDetailsToQueues("INVOICE",$msgBody);
	}

	public function getOrderDetails(){
		$ordersObj = new Billing_Orders();
		$orderDetails = $ordersObj->getOrderForOrderId($this->orderId);
		return $orderDetails;		
	}

	public function setOrderDetailsInCache($orderDetails){

		if(!is_array($orderDetails)){
			throw new Exception("orderDetails not provided");
		}

		$orderIdKey = "oi_".$orderDetails["ORDERID"];

		$connectionObj = RedisConn::getInstance();
		if($connectionObj){
			$connectionObj->setHashObject($orderIdKey,$orderDetails);
		}
	}

	public function addOrderDetailsToQueues($process,$body){
		if(!$process || !is_array($body)){
			throw new Exception("Invalid process or body in addOrderDetailsToQueues");
		}

		$queueData = array(
			'process' => $process,
			'data'	  => array(
						 'body' => $body
						 )
			);
		$producerObj = new Producer();
		$producerObj->sendMessage($queueData);
	}
}

?>
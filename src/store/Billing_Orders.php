<?php


class Billing_Orders{

	protected $connection;

	function __construct(){
		$this->connection = DBConn::getInstance();
	}

	public function getOrderForOrderId($orderId){
		if(!isset($orderId)){
			throw new Exception("Blank orderId passed in getOrderForOrderId");
			return;
		}

		try{
			$sql = "select * from billing.ORDERS where ORDERID = :ORDERID";
			$stmt = $this->connection->db->prepare($sql);
			$stmt->bindParam(':ORDERID', $orderId, PDO::PARAM_STR);
			$stmt->execute();
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$result = $row;
			}
			return $result;
		}
		catch(Exception $ex){
			throw new Exception($ex);
		}
	}

}
?>
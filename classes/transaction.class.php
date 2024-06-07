<?php

	/**
	 * 
	 */
	class Transaction
	{
		private $id;
		private $customer_id;
		private $amount;
		private $stripe_session_id;
		private $dbconn;

		public function setId($id){$this->id = $id;}
		public function getId(){return $this->id;}

		public function setCid($customer_id){$this->customer_id = $customer_id;}
		public function getCid(){return $this->customer_id;}

		public function setAmount($amount){$this->amount = $amount;}
		public function getAmount(){return $this->amount;}

		public function setStripeSessionId($stripe_session_id){$this->stripe_session_id = $stripe_session_id;}
		public function getStripeSessionId(){return $this->stripe_session_id;}
		
		function __construct()
		{
			require_once('../db/DbConnect.php');
      		$db = new DbConnect();
      		$this->dbconn = $db->connect();
		}

		public function AddTransaction(){
			$sql = "INSERT INTO transactions (customer_id, stripe_session_id, amount) VALUES (:customer_id, :stripe_session_id, :amount)";
        	$stmt = $this->dbconn->prepare($sql);
        	$stmt->bindParam('customer_id',$this->customer_id);
        	$stmt->bindParam('stripe_session_id',$this->stripe_session_id);
        	$stmt->bindParam('amount',$this->amount);
        	
        	try{
	        	if($stmt->execute()){
	               return true;
	            }
	            else{
	               return false;
	            }
        	}catch(Exception $e){
	        	echo $e->getMessage();
	        } 
		}
		public function UpdateTransaction(){
			$sql = "UPDATE transactions SET status = 'completed' WHERE stripe_session_id = :stripe_session_id";
        	$stmt = $this->dbconn->prepare($sql);
        	$stmt->bindParam(':stripe_session_id', $this->stripe_session_id);

        	
        	try{
	        	if($stmt->execute()){
	               return true;
	            }
	            else{
	               return false;
	            }
        	}catch(Exception $e){
	        	echo $e->getMessage();
	        } 
		}
		public function TransSession()
		{
			$sql = "DELETE FROM transactions WHERE stripe_session_id = :stripe_session_id";
        	$stmt = $this->dbconn->prepare($sql);
        	$stmt->bindParam(':stripe_session_id', $this->stripe_session_id);

        	
        	try{
	        	if($stmt->execute()){
	               return true;
	            }
	            else{
	               return false;
	            }
        	}catch(Exception $e){
	        	echo $e->getMessage();
	        } 
		}

	}



?>
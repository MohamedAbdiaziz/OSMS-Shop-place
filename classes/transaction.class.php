<?php
include_once('../db/session.php');


	/**
	 * 
	 */
	class Transaction
	{
		private $id;
		private $customer_id;
		private $amount;
		private $stripe_session_id;
		private $description;
		private $dbconn;

		public function setId($id){$this->id = $id;}
		public function getId(){return $this->id;}

		public function setCid($customer_id){$this->customer_id = $customer_id;}
		public function getCid(){return $this->customer_id;}

		public function setAmount($amount){$this->amount = $amount;}
		public function getAmount(){return $this->amount;}

		public function setDesc($description){$this->description = $description;}
		public function getDesc(){return $this->description;}

		public function setStripeSessionId($stripe_session_id){$this->stripe_session_id = $stripe_session_id;}
		public function getStripeSessionId(){return $this->stripe_session_id;}
		
		function __construct()
		{
			require_once('../db/DbConnect.php');
      		$db = new DbConnect();
      		$this->dbconn = $db->connect();
		}

		public function AddTransaction($cartItems){
		    // Begin transaction
		    $this->dbconn->beginTransaction();
		    
		    try {
		        // Insert into transactions table
		        $sql = "INSERT INTO transactions (customer_id, stripe_session_id, amount) VALUES (:customer_id, :stripe_session_id, :amount)";
		        $stmt = $this->dbconn->prepare($sql);
		        $stmt->bindParam(':customer_id', $this->customer_id);
		        $stmt->bindParam(':stripe_session_id', $this->stripe_session_id);
		        $stmt->bindParam(':amount', $this->amount);
		        
		        if(!$stmt->execute()){
		            throw new Exception("Failed to insert into transactions");
		        }

		        // Get the last inserted transaction ID
		        $transactionID = $this->dbconn->lastInsertId();

		        // Insert into tblorders
		        $sql = "INSERT INTO tblorder (Customer, Transaction, Total_Amount) VALUES (:customerID, :transactionID,:Total_Amount)";
		        $stmt = $this->dbconn->prepare($sql);
		        $stmt->bindParam(':customerID', $this->customer_id);
		        $stmt->bindParam(':transactionID', $transactionID);
		        $stmt->bindParam(':Total_Amount', $this->amount);
		        
		        if(!$stmt->execute()){
		            throw new Exception("Failed to insert into tblorders");
		        }

		        // Get the last inserted order ID
		        $orderID = $this->dbconn->lastInsertId();

		        // Insert each cart item into tblorderitems
		        $sql = "INSERT INTO tblorderitem (Order_ID, Product,Quantity, Price) VALUES (:orderID, :productID, :quantity,:price)";
		        $stmt1 = $this->dbconn->prepare($sql);

		        foreach ($cartItems as $item) {
		            $stmt1->bindParam(':orderID', $orderID);
		            $stmt1->bindParam(':productID', $item['ProductID']);
		            $stmt1->bindParam(':quantity', $item['Quantity']);
		            $stmt1->bindParam(':price', $item['ProductPrice']);
		            
		            if(!$stmt1->execute()){
		                throw new Exception("Failed to insert into tblorderitems for productID: " . $item['ProductID']);
		            }

		            // Update the stock
		            $sql = "UPDATE tblstock SET Quantity = Quantity - :quantity	 WHERE Product  = :productID;";
		            $stmt = $this->dbconn->prepare($sql);
		            $stmt->bindParam(':quantity', $item['Quantity']);
		            $stmt->bindParam(':productID', $item['ProductID']);
		            
		            if(!$stmt->execute()){
		                throw new Exception("Failed to update stock for productID: " . $item['ProductID']);
		            }
		            // Check the new stock quantity
				    $sqlCheck = "SELECT Quantity FROM tblstock WHERE Product = :productID;";
				    $stmtCheck = $this->dbconn->prepare($sqlCheck);
				    $stmtCheck->bindParam(':productID', $item['ProductID']);
				    $stmtCheck->execute();
				    $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

				    // If quantity is zero, delete from cartitems
				    if ($result['Quantity'] == 0) {
				        $sqlDelete = "DELETE FROM tblcartitem WHERE Product = :productID;";
				        $stmtDelete = $this->dbconn->prepare($sqlDelete);
				        $stmtDelete->bindParam(':productID', $item['ProductID']);
				        $stmtDelete->execute();
				    }
		        }

		        // Commit transaction
		        $this->dbconn->commit();

		        return true;

		    } catch (Exception $e) {
		        // Rollback transaction if something went wrong
		        $this->dbconn->rollBack();
		        echo $e->getMessage();
		        return false;
		    }
		}

		public function updateTransaction()
		{
		    try {
		        // Fetch customer ID based on stripe session ID
		        $sql = "SELECT customer_id FROM transactions WHERE stripe_session_id = :stripe_session_id";
		        $stmt = $this->dbconn->prepare($sql);
		        $stmt->bindParam(':stripe_session_id', $this->stripe_session_id);
		        $stmt->execute();
		        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
		        
		        if (!$transaction) {
		            throw new Exception('Transaction not found.');
		        }

		        $cid = $transaction['customer_id'];

		        // Update transaction status
		        $sql = "UPDATE transactions SET status = 'completed', Description = :description WHERE stripe_session_id = :stripe_session_id";
		        $stmt = $this->dbconn->prepare($sql);
		        $stmt->bindParam(':stripe_session_id', $this->stripe_session_id);
		        $stmt->bindParam(':description', $this->description);

		        if ($stmt->execute()) {
		             $sql = "DELETE FROM tblcartitem where Customer = :customerID";
			        $stmt = $this->dbconn->prepare($sql);
			        $stmt->bindParam(':customerID', $cid);
			        
			        
			        if(!$stmt->execute()){
			            throw new Exception("Failed to insert into tblorders");
			        }
		        } else {
		            throw new Exception('Failed to update transaction status.');
		        }
		    } catch (Exception $e) {
		        error_log($e->getMessage());
		        return false;
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


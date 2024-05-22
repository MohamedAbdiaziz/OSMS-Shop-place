<?php
   /**
    * 
    */
   class Cart
   {
      private $id;
      private $productId;
      private $quantity;
      private $price;
      private $cid;
      private $dConn;

      public function setId($id){$this->id = $id;}
      public function getId(){return $this->id;}

      public function setProductId($productId){$this->productId = $productId;}
      public function getProductId(){return $this->productId;}

      public function setQuantity($quantity){$this->quantity = $quantity;}
      public function getQuantity(){return $this->quantity;}

      public function setPrice($price){$this->price = $price;}
      public function getPrice(){return $this->price;}

      public function setCid($cid){$this->cid = $cid;}
      public function getCid(){return $this->cid;}

      public function __construct(){
           require_once('../db/DbConnect.php');
         $db = new DbConnect();
         $this->dConn = $db->connect();
      }

      public function AddCart()
      {
         $sql = "INSERT INTO `tblcartitem` (`ID`, `Customer`, `Product`, `Quantity`, `Price`) VALUES (NULL, :cid, :pid, :quantity, :amount)";
         $stmt = $this->dConn->prepare($sql);
         $stmt->bindParam(':cid',$this->cid);
         $stmt->bindParam(':pid',$this->productId);
         $stmt->bindParam(':quantity',$this->quantity);
         $stmt->bindParam(':amount',$this->price);

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
      public function getAllCartItems()
      {
         $sql = "SELECT * FROM `tblcartitem` WHERE Customer= :cid";
         $stmt = $this->dConn->prepare($sql);
         $stmt->bindParam('cid', $this->cid);
         $stmt->execute();
         $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
         return $cartItems;
      }
   }
?>
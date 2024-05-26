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
         $sql = "INSERT INTO `tblcartitem` (`ID`, `Customer`, `Product`, `Quantity`, `Price`,`Subtotal`) VALUES (NULL, :cid, :pid, :quantity, :amount, :subtotal)";
         $stmt = $this->dConn->prepare($sql);
         $stmt->bindParam(':cid',$this->cid);
         $stmt->bindParam(':pid',$this->productId);
         $stmt->bindParam(':quantity',$this->quantity);
         $stmt->bindParam(':amount',$this->price);
         $stmt->bindParam(':subtotal',$this->price*$this->quantity);

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
      public function getCartByID()
      {
         $sql = "SELECT * FROM `tblcartitem` WHERE ID= :id";
         $stmt = $this->dConn->prepare($sql);
         $stmt->bindParam('id', $this->id);
         $stmt->execute();
         $cartItems = $stmt->fetch(PDO::FETCH_ASSOC);
         return $cartItems;
      }
      public function getCartItemsById()
      {
         $sql = "SELECT 
             tblcartitem.ID as CartID,
             tblcartitem.Customer,
             tblcartitem.Quantity,
             tblcartitem.Price as CartPrice,
             tblproduct.ID as ProductID,
             tblproduct.ProductName,
             tblproduct.Description,
             tblproduct.Category,
             tblproduct.DateCreated,
             tblproduct.UpdatedDate,
             tblproduct.Status,
             tblproduct.Type,
             tblproduct.Color,
             tblproduct.Size,
             tblproduct.Price as ProductPrice,
             tblcartitem.Subtotal
         FROM 
             tblcartitem
         JOIN 
             tblproduct 
         ON 
             tblcartitem.Product = tblproduct.ID
         WHERE 
             tblcartitem.Customer = :customerID
         ";
         $stmt = $this->dConn->prepare($sql);
         $stmt->bindParam(':customerID',$this->cid);
         $stmt->execute();
         $cartItem = $stmt->fetchAll(PDO::FETCH_ASSOC);
         return $cartItem;
      }

      public function CartUpdateByID()
      {
          // Prepared statement with placeholders to prevent SQL injection
         $sql = "UPDATE tblcartitem SET Quantity = :quantity, Subtotal=Quantity*Price   WHERE ID = :id";
         $stmt = $this->dConn->prepare($sql);
          
          // Bind parameters to the prepared statement
         $stmt->bindParam(':quantity', $this->quantity, PDO::PARAM_INT);
         $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
         try{
            $stmt->execute();
            return "success";
         }catch (Exception $e){
            return $e->getMessage();
         }
      }

   }
?>
<?php
class Order {
    private $id;
    
    private $customer;
    public $dConn;

    public function setId($id) { $this->id = $id; }
    public function getId() { return $this->id; }

    public function setCustomer($customer) { $this->customer = $customer; }
    public function getCustomer() { return $this->customer; }

    public function __construct() {
        require_once('../db/DbConnect.php');
        $db = new DbConnect();
        $this->dConn = $db->connect();
    }

    public function getOrderById() {
        $sql = "SELECT * FROM tblorder WHERE Customer = :customer;";
        $stmt = $this->dConn->prepare($sql);
        $stmt->bindParam(':customer', $this->customer);
        $stmt->execute();
        $order = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $order;
    }

    public function getAllOrderItems() {
        $stmt = $this->dConn->prepare("SELECT *,p.ProductName FROM tblorderitem o JOIN tblproduct p ON p.ID = o.Product WHERE Order_ID = :id");
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  
}
?>

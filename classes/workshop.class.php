<?php
  class workshop{
    private $id;
    private $productName;
    private $description;
    private $image;
    private $price;

    private $createdOn;
    public $dConn;

    function setId($id){$this->id = $id;}
    function getId(){return $this->id;}
    function setName($productName){$this->productName = $productName;}
    function getName(){return $this->productName;}
    function setDescription($description){$this->description = $description;}
    function getDescription(){return $this->description;}
    function setImage($image){$this->image = $image;}
    function getImage(){return $this->image;}
    function setPrice($price){$this->price = $price;}
    function getPrice(){return $this->price;}
    function setCreatedOn($createdOn){$this->createdOn = $createdOn;}
    function getCreatedOn(){return $this->createdOn;}

    public function __construct()
    {
      require_once('../db/DbConnect.php');
      $db = new DbConnect();
      $this->dConn = $db->connect();
    }

    public function getAllProducts()
    {
      $sql = "SELECT p.ID,p.ProductName,p.Description,p.Category,p.DateCreated,p.UpdatedDate,p.Status,p.Type,p.Color,p.Size,p.Price,s.Quantity, p.Image,s.Status AS StockStatus FROM tblproduct p JOIN tblstock s ON p.ID = s.Product WHERE s.Quantity > 0;";
      $stmt = $this->dConn->prepare($sql);
      $stmt->execute();
      $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $products;
    }

    public function getProductById()
    {
      $sql = "SELECT * FROM `tblproduct` WHERE ID= :pid";
      $stmt = $this->dConn->prepare($sql);
      $stmt->bindParam('pid', $this->id);
      $stmt->execute();
      $products = $stmt->fetch(PDO::FETCH_ASSOC);
      return $products;
    }
  }
?>
<?php
  class workshop{
    private $id;
    private $productName;
    private $description;
    private $image;
    private $price;
    private $cid;
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
    function setCId($cid){$this->cid = $cid;}
    function getCId(){return $this->cid;}

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
    public function get4Products()
    {
      $sql = "SELECT * FROM tblproduct WHERE Category=$this->cid AND Status='Active' LIMIT 4";
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
    public function getProduct()
    {
      
    $sql = "SELECT 
    p.ID, 
    p.Category,
    p.ProductName, 
    p.Description, 
    p.Price, 
    p.Image, 
    p.Color, 
    p.Size, 
    p.Type, 
    s.Quantity AS stock_quantity,
    c.Name As category_name
FROM 
    tblproduct p
JOIN 
    tblstock s ON p.ID = s.Product
JOIN 
    tblcategory c ON p.Category = c.ID
WHERE 
    p.ID = :pid;
";
      $stmt = $this->dConn->prepare($sql);
      $stmt->bindParam('pid', $this->id);
      $stmt->execute();
      $products = $stmt->fetch(PDO::FETCH_ASSOC);
      return $products;

    }
    public function getProductsByCategory()
    {
      $sql = "SELECT ID, ProductName, Price, Description, Image FROM tblproduct WHERE Category = :cid";
      $stmt = $this->dConn->prepare($sql);
      $stmt->bindParam('cid', $this->cid);
      $stmt->execute();
      $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $products;
    }
  }
?>
<?php
  class customer{
    private $id;
    private $name;
    private $username;
    private $image;
    private $createdOn;
    private $email;
    private $password;
    public $dConn;

    public function setId($id){$this->id = $id;}
    public function getId(){return $this->id;}
    public function setName($name){$this->name = $name;}
    public function getName(){return $this->name;}
    public function setUsername($username){$this->username = $username;}
    public function getUsername(){return $this->username;}
    public function setImage($image){$this->image = $image;}
    public function getImage(){return $this->image;}
    public function setCreatedOn($createdOn){$this->createdOn = $createdOn;}
    public function getCreatedOn(){return $this->createdOn;}
    public function setEmail($email){$this->email = $email;}
    public function getEmail(){return $this->email;}
    public function setPassword($password){$this->password = $password;}
    public function getPassword(){return $this->password;}
    

    public function __construct()
    {
      require_once('../db/DbConnect.php');
      $db = new DbConnect();
      $this->dConn = $db->connect();
    }

    public function getCustomerById()
    {
      $sql = "SELECT * FROM tblcustomer WHERE Username = :cid";
      $stmt = $this->dConn->prepare($sql);
      $stmt->bindParam('cid', $this->username);
      $stmt->execute();
      $Customer = $stmt->fetch(PDO::FETCH_ASSOC);
      
      return $Customer;
    }

    public function registercustomer()
    {
      // code...
    }
  }
?>
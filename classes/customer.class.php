<?php
  class customer{
    private $id;
    private $name;
    private $username;
    private $image;
    private $createdOn;
    private $email;
    private $password;
    private $newPassword;
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
    public function setNewPassword($newPassword){$this->newPassword = $newPassword;}
    // public function setPassword($password){$this->password = $password;}
    

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
    public function change_password()
    {
      $sql = "SELECT Password FROM tblcustomer WHERE Username = :username";
      $stmt = $this->dConn->prepare($sql);
      $stmt->bindParam(':username', $this->username);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      $hashedPassword = md5($this->newPassword);
      
      if (md5($this->password) != $result['Password']) {
          $_SESSION['error'] = "Current password is incorrect.";
          
          header("Location: ../pages/change_password.php");
          exit();
      }

      // Hash the new password
      

      // Update the password in the database
      $sql = "UPDATE tblcustomer SET Password = :newPassword WHERE Username = :username";
      $stmt = $this->dConn->prepare($sql);
      $stmt->bindParam(':newPassword', $hashedPassword);
      $stmt->bindParam(':username', $this->username);

      if ($stmt->execute()) {
          $_SESSION['success'] = "Password changed successfully.";
      } else {
          $_SESSION['error'] = "Failed to change password. Please try again.";
      }

      header("Location: ../pages/change_password.php");
    }

    public function registercustomer()
    {
      // code...
    }
  }
?>
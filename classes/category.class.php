<?php
	/**
	 * 
	 */
	class Category
	{
		private $id;
		private $dbconn;

		public function setid($id)
		{
			$this->id = $id;
		}
		public function getid()
		{
			return $this->id;
		}

		function __construct()
		{
			require_once "../db/DbConnect.php";
			$db = new DbConnect();
			$this->dbconn = $db->connect();			
		}
		public function getCategories() {
		    $sql = "SELECT id, name, description, Image FROM tblcategory";
		    $stmt = $this->dbconn->prepare($sql);
		    $stmt->execute();
	        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
	        return $categories;
		}
		public function getCategory()
		{
			$sql = "SELECT * FROM tblcategory WHERE Status='Active' Limit 3";
			$stmt = $this->dbconn->prepare($sql);
		    $stmt->execute();
	        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
	        return $categories;
		}


	}


?>
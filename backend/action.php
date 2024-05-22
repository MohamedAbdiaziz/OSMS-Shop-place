<?php
// header('Content-Type: application/json');
session_save_path('../db');
  ini_set('session.gc_probability', 1);
session_start();
	if (isset($_POST['action'])) {
		require '../classes/workshop.class.php';
		require '../classes/customer.class.php';
		require '../classes/cart.class.php';
		switch ($_POST['action']) {
			case 'add':	
				// all objects
				$objworkshop = new workshop();
				$objCustomer = new customer();
				$objcart = new cart();


				$objworkshop->setId($_POST['pID']);
				// echo $objworkshop->getId();

				$objcart->setCid($_SESSION['customer']);	
				// echo $objcart->getCid();
				$objcart->setProductId($_POST['pID']);	
				// echo $objcart->getProductId();
				$objcart->setQuantity(1);	
				// echo $objcart->getQuantity();
				$prod = $objworkshop->getProductById();
				$objcart->setPrice($prod['Price']);	
				// echo $objcart->getPrice();

				if($objcart->AddCart()){
					echo json_encode(["status"=>1,"msg"=>"Added to Cart"]);
					exit;
				}
				else{
					echo json_encode(["status"=>0,"msg"=>"Failed to add cart"]);
					exit;					
				}
				

             	
				break;
			
			default:
				// code...
				break;
		}
	}else{
		header('location: ../pages/index.php');
		// echo $_SESSION['customer'];
	}
?>
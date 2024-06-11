<?php
// header('Content-Type: application/json');
session_save_path('../db');
  ini_set('session.gc_probability', 1);
session_start();
	if (isset($_POST['action'])) {
		require '../classes/workshop.class.php';
		require '../classes/customer.class.php';
		require '../classes/cart.class.php';
		$objworkshop = new workshop();
		$objCustomer = new customer();
		$objcart = new cart();
		switch ($_POST['action']) {
			case 'add':	
				// all objects				
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

			case 'update':	
				// all objects				
				// $objworkshop->setId($_POST['cartID']);
				// echo $objworkshop->getId();

				$objcart->setCid($_SESSION['customer']);	
				// echo $objcart->getCid();
				$objcart->setID($_POST['cartID']);	
				// echo $objcart->getID();
				$objcart->setQuantity($_POST['quantity']);	
				// echo $objcart->getQuantity();
				// $prod = $objworkshop->getProductById();
				// $objcart->setPrice($prod['Price']);	
				// // echo $objcart->getPrice();

				if($objcart->CartUpdateByID() == "success"){
					
					$cartItems = $objcart->getAllCartItems();
          $subtotal = 0;
          $total = 0;
          foreach ($cartItems as $key => $product) { 
          	$subtotal	+= $product['Price'] * $product['Quantity'];
          }

          $cartprice = $objcart->getCartByID();
          // echo $cartprice['Subtotal'];
          // echo	$subtotal;
          $data = ["subtotal"=>$cartprice['Subtotal'],"total"=>$subtotal];
          echo json_encode(["status"=>1,"msg"=>"Added to Cart","data"=>$data]);
				}else{
					echo "Quantity is Greater Than available quantity or Anything Else";
				}						         
				break;

			case 'remove':
				$objcart->setCid($_SESSION['customer']);	
				$objcart->setID($_POST['CartID']);	
				
				if($objcart->removeByID()){
					$cartItems = $objcart->getAllCartItems();
          $subtotal = 0;
          $total = 0;
          foreach ($cartItems as $key => $product) { 
          	$subtotal	+= $product['Price'] * $product['Quantity'];
          }

          
          $data = ["total"=>$subtotal];
          echo json_encode(["status"=>1,"msg"=>"Added to Cart","data"=>$data]);
				}
				else{
					echo json_encode(["status"=>0,"msg"=>"Failed to Cart"]);
				}


				break;

			case 'removeall':
				$objcart->setCid($_SESSION['customer']);	
				
				
				if($objcart->revomeAll()){					
          echo json_encode(["status"=>1,"msg"=>"Added to Cart"]);
				}
				else{
					echo json_encode(["status"=>0,"msg"=>"Failed to Cart"]);
				}


				break;


			default:
				// code...
				break;
		}
	}
	else{
		header('location: ../pages/index.php');
		// echo $_SESSION['customer'];
	}
?>
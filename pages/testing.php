<?php
include_once('../db/session.php');

$objTrans = new Transaction();
            $objCart = new Cart();
            $objTrans->setStripeSessionId();
            $objCart->setCid($_SESSION['customer']);
            $objTrans->UpdateTransaction();
            $objCart->removeAll();

?>
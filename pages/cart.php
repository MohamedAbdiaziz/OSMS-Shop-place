<?php 
include_once('../db/session.php');
include_once("../hf/header.php");
if (!isset($_SESSION['customer'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}?>


  <section id="banner" class="py-3" style="background: #F9F3EC;">
    <div class="container">
      <div class="hero-content py-5 my-3">
        <h2 class="display-1 mt-3 mb-0">Cart</h2>
        <nav class="breadcrumb">
          <a class="breadcrumb-item nav-link" href="#">Home</a>
          <a class="breadcrumb-item nav-link" href="#">Pages</a>
          <span class="breadcrumb-item active" aria-current="page">Cart</span>
        </nav>
      </div>
    </div>
  </section>

  <section id="cart" class="my-5 py-5">
    <div class="container">

      <div class="row g-md-5">

        <div class="col-md-8 pe-md-5">
          <table class="table">
            <thead>
              <tr>
                <th scope="col" class="card-title text-uppercase">Product</th>
                <th scope="col" class="card-title text-uppercase">Quantity</th>
                <th scope="col" class="card-title text-uppercase">Price</th>
                <th scope="col" class="card-title text-uppercase">Subtotal</th>
                <th scope="col" class="card-title text-uppercase"></th>
              </tr>
            </thead>
            <tbody>
              <?php

                $cid = $_SESSION['customer'];
                require_once '../classes/cart.class.php';
                $objCartItem = new cart();
                $objCartItem->setCid($cid);
                // $customer = $objCustomer->getCustomerById();

                // $_SESSION['customer']=$objCustomer->getUsername();

                require_once '../classes/workshop.class.php';
                // $objProducts = new workshop();
                $cartItems = $objCartItem->getCartItemsById();
                $subtotal = 0;
                $total;
                foreach ($cartItems as $key => $product) {                          
              ?>
              <tr id="row_<?= $product['CartID'];?>">
                <td scope="row" class="py-4">
                  <div class="cart-info d-flex flex-wrap align-items-center ">
                    <div class="col-lg-3">
                      <div class="card-image">
                        <img src="../images/Category/<?= $product['Image'];?>" alt="cloth" class="img-fluid">
                      </div>
                    </div>
                    <div class="col-lg-9">
                      <div class="card-detail ps-3">
                        <h5 class="card-title">
                          <a href="single-product.php?id=<?= $product['ProductID'];?>" class="text-decoration-none"><?=$product['ProductName'];?></a>
                        </h5>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="py-4 align-middle">
                  <div class="input-group product-qty align-items-center w-50">
                    <!-- <span class="input-group-btn">
                      <button type="button" class="quantity-left-minus btn btn-light btn-number" data-type="minus">
                        <svg width="16" height="16">
                          <use xlink:href="#minus"></use>
                        </svg>
                      </button>
                    </span> -->

                    <input type="text" onchange="updateCart(<?= $product['CartID'];?>)" id="quantity_<?= $product['CartID'];?>" name="quantity" class="form-control input-number text-center p-2 mx-1" value="<?=$product['Quantity'];?>">
                    <!-- <span class="input-group-btn">
                      <button type="button" class="quantity-right-plus btn btn-light btn-number" data-type="plus" data-field="">
                        <svg width="16" height="16">
                          <use xlink:href="#plus"></use>
                        </svg>
                      </button>
                    </span> -->
                  </div>
                </td>
                <td class="py-4 align-middle">
                  <div class="total-price">
                    <span class="secondary-font fw-medium"><?= number_format($product['CartPrice'], 2);?></span>
                  </div>
                </td>
                <td class="py-4 align-middle">
                  <div class="total-price">
                    <span class="secondary-font fw-medium" id="subtotal_<?= $product['CartID'];?>"><?= number_format($product['Subtotal'], 2)?></span>
                  </div>
                </td>
                <td class="py-4 align-middle">
                  <div class="cart-remove">
                    <button  onclick="removeCartItem(<?= $product['CartID'];?>)">
                      <svg width="24" height="24">
                        <use xlink:href="#trash"></use>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
              <?php $subtotal += number_format($product['CartPrice'] * $product['Quantity'], 2);
              
              ?>
              <?php } ?>
              
              

            </tbody>
          </table>
        </div>
        <div class="col-md-4">
          <div class="cart-totals">
            <h2 class="pb-4">Cart Total</h2>
            <div class="total-price pb-4">
              <table cellspacing="0" class="table text-uppercase">
                <tbody>
                  <tr class="subtotal pt-2 pb-2 border-top border-bottom">
                    <th>Subtotal</th>
                    <td data-title="Subtotal">
                      <span class="price-amount amount text-dark ps-5">
                        <bdi>
                          <span class="price-currency-symbol">$</span><span id="subtotal_total"><?php echo $subtotal;?></span>
                        </bdi>
                      </span>
                    </td>
                  </tr>
                  <!-- <tr class="order-total pt-2 pb-2 border-bottom">
                    <th>Discount</th>
                    <td data-title="Total">
                      <span class="price-amount amount text-dark ps-5">
                        <bdi>
                          <span class="price-currency-symbol">%</span></bdi>
                      </span>
                    </td>
                  </tr> -->
                  <tr class="order-total pt-2 pb-2 border-bottom">
                    <th>Total</th>
                    <td data-title="Total">
                      <span class="price-amount amount text-dark ps-5">
                        <bdi>
                          <span class="price-currency-symbol">$</span><span id="subtotal_alltotal"><?php echo $total=$subtotal;?></span></bdi>
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="button-wrap row g-2">
              <div class="col-md-6"><button class="btn btn-danger btn-lg rounded-1 fs-6 p-3 w-100" onclick="removeAllCartItems()">Clear Cart</button>
              </div>
              <div class="col-md-6"><button class="btn btn-dark btn-lg rounded-1 fs-6 p-3 w-100" onclick="if(window.history.back() == true){window.history.back()}else{window.location.href('shop.php')}">Continue To
                  Shop</button></div>
              <div class="col-md-12">
                <!-- <button class="btn btn-primary p-3 text-uppercase rounded-1 w-100" id="checkout-button">Proceed to checkout</button> -->
                <button class="btn btn-primary p-3 text-uppercase rounded-1 w-100" id="checkout-button">Checkout</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>






  <?php include_once("../hf/footer.php");?>
  
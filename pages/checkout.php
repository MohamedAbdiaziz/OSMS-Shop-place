<?php 
include_once('../db/session.php');
include_once("../hf/header.php");

$customer = $objCustomer->getCustomerById();
$cart = $objCart->GetCartSubtotalSum();


?>



  <section id="banner" class="py-3" style="background: #F9F3EC;">
    <div class="container">
      <div class="hero-content py-5 my-3">
        <h2 class="display-1 mt-3 mb-0">Checkout</h2>
        <nav class="breadcrumb">
          <a class="breadcrumb-item nav-link" href="#">Home</a>
          <a class="breadcrumb-item nav-link" href="#">Pages</a>
          <span class="breadcrumb-item active" aria-current="page">Checkout</span>
        </nav>
      </div>
    </div>
  </section>

  <section class="shopify-cart checkout-wrap">
    <div class="container py-5 my-5">
      <form class="form-group">
        <div class="row d-flex flex-wrap">
          <div class="col-lg-6">
            <h2 class="text-dark pb-3">Billing Details</h2>
            <div class="billing-details">
              <label for="fname">Full Name*</label>
              <input type="text" id="fname" name="firstname" class="form-control mt-2 mb-4 ps-3" value="<?=$customer['Name'];?>">
              <label for="phone">Phone *</label>
              <input type="tell" id="phone" name="phone" class="form-control mt-2 mb-4 ps-3" value="<?=$customer['Mobile'];?>">
              <label for="email">Email address *</label>
              <input type="text" id="email" name="email" class="form-control mt-2 mb-4 ps-3" value="<?=$customer['Email'];?>">

              <label for="cname">Region*</label>
              <select class="form-select form-control mt-2 mb-4" aria-label="Default select example">
                <option selected="" value="Mogadishu">Mogadishu</option>
                
              </select>
              <label for="address">Address*</label>              
              <input type="text" id="adr" name="address" placeholder="village,Street" class="form-control ps-3 mb-4" value="<?=$customer['Address'];?>">
                            
            </div>
          </div>
          <div class="col-lg-6">
            <h2 class="text-dark pb-3">Additional Information</h2>
            <!-- <div class="billing-details">
              <label for="fname">Order notes (optional)</label>
              <textarea class="form-control pt-3 pb-3 ps-3 mt-2" placeholder="Notes about your order. Like special notes for delivery."></textarea>
            </div> -->
            <div class="your-order mt-5">
              <h2 class="display-7 text-dark pb-3">Cart Totals</h2>
              <div class="total-price">
                <table cellspacing="0" class="table">
                  <tbody>
                    <tr class="subtotal border-top border-bottom pt-2 pb-2 text-uppercase">
                      <th>Subtotal</th>
                      <td data-title="Subtotal">
                        <span class="price-amount amount ps-5">
                          <bdi>
                            <span class="price-currency-symbol">$</span><?= $cart['sum'];?></bdi>
                        </span>
                      </td>
                    </tr>
                    <tr class="order-total border-bottom pt-2 pb-2 text-uppercase">
                      <th>Total</th>
                      <td data-title="Total">
                        <span class="price-amount amount ps-5">
                          <bdi>
                            <span class="price-currency-symbol">$</span><?= $cart['sum'];?> </bdi>
                        </span>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <!-- <div class="list-group mt-5 mb-3">
                  <label class="list-group-item d-flex gap-2 border-0">
                    <input class="form-check-input flex-shrink-0" type="radio" name="listGroupRadios" id="listGroupRadios1" value="" checked="">
                    <span>
                      <strong class="text-uppercase">Direct bank transfer</strong>
                      <small class="d-block text-body-secondary">Make your payment directly into our bank account.
                        Please use your Order ID. Your order will shipped after funds have cleared in our
                        account.</small>
                    </span>
                  </label>
                  <label class="list-group-item d-flex gap-2 border-0">
                    <input class="form-check-input flex-shrink-0" type="radio" name="listGroupRadios" id="listGroupRadios2" value="">
                    <span>
                      <strong class="text-uppercase">Check payments</strong>
                      <small class="d-block text-body-secondary">Please send a check to Store Name, Store Street, Store
                        Town, Store State / County, Store Postcode.</small>
                    </span>
                  </label>
                  <label class="list-group-item d-flex gap-2 border-0">
                    <input class="form-check-input flex-shrink-0" type="radio" name="listGroupRadios" id="listGroupRadios3" value="">
                    <span>
                      <strong class="text-uppercase">Cash on delivery</strong>
                      <small class="d-block text-body-secondary">Pay with cash upon delivery.</small>
                    </span>
                  </label>
                  <label class="list-group-item d-flex gap-2 border-0">
                    <input class="form-check-input flex-shrink-0" type="radio" name="listGroupRadios" id="listGroupRadios3" value="">
                    <span>
                      <strong class="text-uppercase">Paypal</strong>
                      <small class="d-block text-body-secondary">Pay via PayPal; you can pay with your credit card if
                        you don’t have a PayPal account.</small>
                    </span>
                  </label>
                </div> -->
                <button type="submit" name="submit" class="btn btn-dark btn-lg rounded-1 w-100">Pay Now</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>

  <?php include_once("../hf/footer.php");?>
<footer id="footer" class="my-5">
  <div class="container py-5 my-5">
    <div class="row">
      <div class="col-md-4">
        <div class="footer-menu">
          <img src="../images/logo.png" alt="logo">
          <p class="blog-paragraph fs-6 mt-3">Subscribe to our newsletter to get updates about our grand offers.</p>
          <div class="social-links">
            <ul class="d-flex list-unstyled gap-2">
              <li class="social">
                <a href="#">
                  <iconify-icon class="social-icon" icon="ri:facebook-fill"></iconify-icon>
                </a>
              </li>
              <li class="social">
                <a href="#">
                  <iconify-icon class="social-icon" icon="ri:twitter-fill"></iconify-icon>
                </a>
              </li>
              <li class="social">
                <a href="#">
                  <iconify-icon class="social-icon" icon="ri:pinterest-fill"></iconify-icon>
                </a>
              </li>
              <li class="social">
                <a href="#">
                  <iconify-icon class="social-icon" icon="ri:instagram-fill"></iconify-icon>
                </a>
              </li>
              <li class="social">
                <a href="#">
                  <iconify-icon class="social-icon" icon="ri:youtube-fill"></iconify-icon>
                </a>
              </li>

            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="footer-menu">
          <h3>Quick Links</h3>
          <ul class="menu-list list-unstyled">
            <li class="menu-item">
              <a href="#" class="nav-link">Home</a>
            </li>
            <li class="menu-item">
              <a href="#" class="nav-link">Product</a>
            </li>
            <li class="menu-item">
              <a href="#" class="nav-link">Contact </a>
            </li>
            <li class="menu-item">
              <a href="#" class="nav-link">Conatct Us</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="col-md-4">
        <div class="footer-menu">
          <h3>Help Center</h5>
            <ul class="menu-list list-unstyled">
              <li class="menu-item">
                <a href="#" class="nav-link">FAQs</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link">Payment</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link">Returns & Refunds</a>
              </li>
              <li class="menu-item">
                <a href="#" class="nav-link">Checkout</a>
              </li>
              
            </ul>
        </div>
      </div>
      

    </div>
  </div>
</footer>

<div id="footer-bottom">
  <div class="container">
    <hr class="m-0">
    <div class="row mt-3">
      <div class="col-md-6 copyright">
        <p class="secondary-font">© 2024 OSMS. All rights reserved.</p>
      </div>
      
    </div>
  </div>
</div>


<script src="../js/jquery-1.11.0.min.js"></script>
<script src="../js/swiper.js"></script>
<script src="../js/bootstrap.bundle.js"></script>
<script src="../js/plugins.js"></script>
<script src="../js/script.js"></script>
<script src="../js/iconify.js"></script>
  <script src="https://js.stripe.com/v3/"></script>

<script type="text/javascript">
  function addToCart(pID, btnID) {
    var myToastEl = document.getElementById('myToast');
    var myToast = new bootstrap.Toast(myToastEl, {
        delay: 3000 // 3 seconds
    });

    $.ajax({
      url: "../backend/action.php",
      data: "pID=" + pID + "&action=add",
      method: "post"
    }).done(function(response){
      // alert(response);
      try {
        var data = JSON.parse(response);
        if (data.status == 1) {
          $('#'+btnID).prop('disabled',true);
          $('#itemCount').text(parseInt($('#itemCount').text())+1);
          $('.toast-body').html(data.msg);
          $('.toast').addClass('bg-success');
          myToast.show();
        }
         else {
          $('.toast').addClass('bg-danger');          
          $('.toast-body').html(data.msg);
          myToast.show();
        }
      } catch (e) {
        alert('Failed to parse JSON response: '+response);
      }
    })
  }

  function updateCart(cartID) {
    // console.log(cartID+$('#quantity_'+cartID).val());
    $.ajax({
      url: "../backend/action.php",
      data: "cartID=" + cartID + "&action=update&quantity="+$('#quantity_'+cartID).val(),
      method: "post"
    }).done(function (response) {
      try {
        var data = JSON.parse(response);

        if (data.status == 1) {
          $('#subtotal_'+cartID).text(data.data.subtotal);
          $('#subtotal_total').html(data.data.total);
          $('#subtotal_alltotal').text(data.data.total);
          
          
        }
         
      } catch (e) {
        
        alert("Sorry!! Quantity is Greater Than available quantity(see in signle product page) or Anything Else");
        location.reload();
      }
    })
  }

  function removeCartItem(CartID){
    $.ajax({
      url: "../backend/action.php",
      data: "CartID=" + CartID + "&action=remove",
      method: "post"
    }).done(function (response) {
        // console.log(response);
        var data = JSON.parse(response);
        if (data.status == 1) {
          // $('#subtotal_'+CartID).text(data.data.subtotal);
          $('#subtotal_total').html(data.data.total);
          $('#subtotal_alltotal').text(data.data.total);          
          $('#row_'+CartID).text("");
        }

        
        // console.log(element.textContent);
    })
  }
  function  removeAllCartItems(){
    if(confirm("Are you Sure to Clear All Cart Items?")){
      $.ajax({
        url: "../backend/action.php",
        data: "action=removeall",
        method: "post"
      }).done(function (response) {
          console.log(response);
          var data = JSON.parse(response);
          if (data.status == 1) {
            // $('#subtotal_'+CartID).text(data.data.subtotal);
            location.reload();
            alert('Cleared');
          }
          else{
            alert("Failed To Remove all Cart Items :)");
          }

          
          // console.log(element.textContent);
      })
    }
    
  }

</script>
<script>
        const stripe = Stripe('pk_test_51PMXeh08OHR1fd54PgSByK6jF9WIWJY3hOwuxqU5gVpTBRCNNjb2spzeRMCAuuEZOall4UILV5JLvENXkX9KgsUQ00xpPqhEUh');

        document.getElementById('checkout-button').addEventListener('click', async () => {
          confirm("Are you Sure to Clear All Cart Items?");
            // const response = await fetch('../backend/create-checkout-session.php', {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json',
            //     },
            // });
            
            // const session = await response.json();
            // // stripe.redirectToCheckout({ sessionId: session.id });
            $.ajax({
              url: "../backend/create-checkout-session.php",
              data: "action=removeall",
              method: "post"
            }).done(function (response) {
                console.log(response);
                const session = response;
                console.log(session);
confirm("Are you Sure to Clear All Cart Items?");
                stripe.redirectToCheckout({sessionId: session.id });

                

                
                // console.log(element.textContent);
            });
            confirm("Are you Sure to Clear All Cart Items?")
        });
    </script>

</body>

</html>
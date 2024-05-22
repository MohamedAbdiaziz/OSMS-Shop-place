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
        console.error('Failed to parse JSON response: ', response);
      }
    })
  }
</script>

</body>

</html>
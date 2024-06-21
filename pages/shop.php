

<?php include_once("../hf/header.php"); ?>

  <section id="banner" class="py-3" style="background: #F9F3EC;">
    <div class="container">
      <div class="hero-content py-5 my-3">
        <h2 class="display-1 mt-3 mb-0">Shop</h2>
        <nav class="breadcrumb">
          <a class="breadcrumb-item nav-link" href="#">Home</a>
          <a class="breadcrumb-item nav-link" href="#">Pages</a>
          <span class="breadcrumb-item active" aria-current="page">Shop</span>
        </nav>
      </div>
    </div>
  </section>
<?php 
    if(isset($_SESSION['py_success'])){
        echo $_SESSION['py_success'];
        unset($_SESSION['py_success']);
    }

?>
  <div class="shopify-grid">
    <div class="container py-5 my-5">
      <div class="row g-md-5 mb-5">

        <main class="col-md-12">
          

          <div class="product-grid row ">
            <?php

              require_once '../classes/customer.class.php';
              $objCustomer = new customer();
              $objCustomer->setUsername("Yussuf488");
              $customer = $objCustomer->getCustomerById();

              // $_SESSION['customer']=$objCustomer->getUsername();

              require_once '../classes/workshop.class.php';
              $objProducts = new workshop();
              $products = $objProducts->getAllProducts();
              foreach ($products as $key => $product) {                          
            ?>
              <div class="col-md-4 my-4">
                <!-- <div class="z-1 position-absolute rounded-3 m-3 px-3 border border-dark-subtle">
                  New
                </div> -->
                <div class="card position-relative">
                  <a href="single-product.php?id=<?= $product['ID'];?>"><img src="../images/Category/<?= $product['Image'];?>" class="img-fluid rounded-4" alt="image"></a>
                  <div class="card-body p-0">
                    <a href="single-product.php?id=<?= $product['ID'];?>">
                      <h3 class="card-title pt-4 m-0"><?= $product['ProductName'];?></h3>
                    </a>

                    <div class="card-text">
                      

                      <h3 class="secondary-font text-primary">$<?= $product['Price'];?></h3>

                      <?php if(isset($_SESSION['customer'])){?>
                        <div class="d-flex flex-wrap mt-3">
                          <?php
                            $cartItems = $objCart->getAllCartItems();
                            $cartProductIds = array_column($cartItems, 'Product');
                            $disabled = "";
                            if (in_array($product['ID'], $cartProductIds)) {
                                $disabled = "disabled";
                            }
                          ?>
                          <button id="cartBtn_<?= $product['ID'];?>" role="button" class="btn-cart me-3 px-4 pt-3 pb-3" onclick="addToCart(<?= $product['ID'];?>,this.id);" <?php echo $disabled;?>>
                            <h5 class="text-uppercase m-0" >Add to Cart</h5>
                          </button>
                          <a href="#" class="btn-wishlist px-4 pt-3 ">
                            <iconify-icon icon="fluent:heart-28-filled" class="fs-5"></iconify-icon>
                          </a>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            <?php } ?>
            <!-- <div class="col-md-4 my-4">
              <div class="z-1 position-absolute rounded-3 m-3 px-3 border border-dark-subtle">
                New
              </div>
              <div class="card position-relative">
                <a href="single-product.html"><img src="images/item10.jpg" class="img-fluid rounded-4" alt="image"></a>
                <div class="card-body p-0">
                  <a href="single-product.html">
                    <h3 class="card-title pt-4 m-0">Grey hoodie</h3>
                  </a>

                  <div class="card-text">
                    <span class="rating secondary-font">
                      <iconify-icon icon="clarity:star-solid" class="text-primary"></iconify-icon>
                      <iconify-icon icon="clarity:star-solid" class="text-primary"></iconify-icon>
                      <iconify-icon icon="clarity:star-solid" class="text-primary"></iconify-icon>
                      <iconify-icon icon="clarity:star-solid" class="text-primary"></iconify-icon>
                      <iconify-icon icon="clarity:star-solid" class="text-primary"></iconify-icon>
                      5.0</span>

                    <h3 class="secondary-font text-primary">$18.00</h3>

                    <div class="d-flex flex-wrap mt-3">
                      <a href="#" class="btn-cart me-3 px-4 pt-3 pb-3">
                        <h5 class="text-uppercase m-0">Add to Cart</h5>
                      </a>
                      <a href="#" class="btn-wishlist px-4 pt-3 ">
                        <iconify-icon icon="fluent:heart-28-filled" class="fs-5"></iconify-icon>
                      </a>
                    </div>


                  </div>

                </div>
              </div>
            </div> -->            
          </div>
          <!-- / product-grid -->

          <!-- <nav class="navigation paging-navigation text-center mt-5" role="navigation">
            <div class="pagination loop-pagination d-flex justify-content-center align-items-center">
              <a href="#" class="pagination-arrow d-flex align-items-center mx-3">
                <iconify-icon icon="ic:baseline-keyboard-arrow-left" class="pagination-arrow fs-1"></iconify-icon>
              </a>
              <span aria-current="page" class="page-numbers mt-2 fs-3 mx-3 current">1</span>
              <a class="page-numbers mt-2 fs-3 mx-3" href="#">2</a>
              <a class="page-numbers mt-2 fs-3 mx-3" href="#">3</a>
              <a href="#" class="pagination-arrow d-flex align-items-center mx-3">
                <iconify-icon icon="ic:baseline-keyboard-arrow-right" class="pagination-arrow fs-1"></iconify-icon>
              </a>
            </div>
          </nav> -->

        </main>
        <!-- <aside class="col-md-3 mt-5">
          <div class="sidebar">
            <div class="widget-menu">
              <div class="widget-search-bar">
                <div class="search-bar border rounded-2 border-dark-subtle pe-3">
                  <form id="search-form" class="text-center d-flex align-items-center" action="" method="">
                    <input type="text" class="form-control border-0 bg-transparent" placeholder="Search for products">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                      <path fill="currentColor" d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z"></path>
                    </svg>
                  </form>
                </div>
              </div>
            </div>
            <div class="widget-product-categories pt-5">
              <h4 class="widget-title">Categories</h4>
              <ul class="product-categories sidebar-list list-unstyled">
                <li class="cat-item">
                  <a href="/collections/categories">All</a>
                </li>
                <li class="cat-item">
                  <a href="#" class="nav-link">Dogs</a>
                </li>
                <li class="cat-item">
                  <a href="#" class="nav-link">Food</a>
                </li>
                <li class="cat-item">
                  <a href="#" class="nav-link">Cats</a>
                </li>
                <li class="cat-item">
                  <a href="#" class="nav-link">Birds</a>
                </li>
              </ul>
            </div>
            <div class="widget-product-tags pt-3">
              <h4 class="widget-title">Tags</h4>
              <ul class="product-tags sidebar-list list-unstyled">
                <li class="tags-item">
                  <a href="#" class="nav-link">Pets</a>
                </li>
                <li class="tags-item">
                  <a href="#" class="nav-link">Clothes</a>
                </li>
                <li class="tags-item">
                  <a href="#" class="nav-link">Foods</a>
                </li>
                <li class="tags-item">
                  <a href="#" class="nav-link">Toys</a>
                </li>
              </ul>
            </div>
            <div class="widget-product-brands pt-3">
              <h4 class="widget-title">Brands</h4>
              <ul class="product-tags sidebar-list list-unstyled">
                <li class="tags-item">
                  <a href="#" class="nav-link">Denim</a>
                </li>
                <li class="tags-item">
                  <a href="#" class="nav-link">Puma</a>
                </li>
                <li class="tags-item">
                  <a href="#" class="nav-link">Klaws</a>
                </li>
              </ul>
            </div>
            <div class="widget-price-filter pt-3">
              <h4 class="widget-titlewidget-title">Filter By Price</h4>
              <ul class="product-tags sidebar-list list-unstyled">
                <li class="tags-item">
                  <a href="#" class="nav-link">Less than $10</a>
                </li>
                <li class="tags-item">
                  <a href="#" class="nav-link">$10- $20</a>
                </li>
                <li class="tags-item">
                  <a href="#" class="nav-link">$20- $30</a>
                </li>
                <li class="tags-item">
                  <a href="#" class="nav-link">$30- $40</a>
                </li>
                <li class="tags-item">
                  <a href="#" class="nav-link">$40- $50</a>
                </li>
              </ul>
            </div>
          </div>
        </aside> -->
      </div>
    </div>
  </div>

  
  
  <?php include_once("../hf/footer.php"); ?>
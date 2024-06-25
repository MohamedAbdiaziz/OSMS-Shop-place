<?php
$title = "Home";
 include_once("../hf/header.php"); 
require_once("../classes/category.class.php");
require_once("../classes/workshop.class.php");?>

  <section id="banner" style="background: #ffff;">
    <div class="container">
      <div class="swiper main-swiper">
        <div class="swiper-wrapper">

          <div class="swiper-slide py-5">
            <div class="row banner-content align-items-center">
              <div class="img-wrapper col-md-5">
                <img src="../images/op/ew4.jpg" class="img-fluid">
              </div>
              <div class="content-wrapper col-md-7 p-5 mb-5">
                <!-- <div class="secondary-font text-primary text-uppercase mb-4">Save 10 - 20 % off</div> -->
                <h2 class="banner-title display-1 fw-normal">Best Design for <span class="text-primary">your
                    frame</span>
                </h2>
                <a href="#" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">
                  shop now
                  <svg width="24" height="24" viewBox="0 0 24 24" class="mb-1">
                    <use xlink:href="#arrow-right"></use>
                  </svg></a>
              </div>

            </div>
          </div>
          <div class="swiper-slide py-5">
            <div class="row banner-content align-items-center">
              <div class="img-wrapper col-md-5">
                <img src="../images/op/ew5.jpg" class="img-fluid">
              </div>
              <div class="content-wrapper col-md-7 p-5 mb-5">
                <!-- <div class="secondary-font text-primary text-uppercase mb-4">Save 10 - 20 % off</div> -->
                <h2 class="banner-title display-1 fw-normal">Best Design for <span class="text-primary">your
                    lens</span>
                </h2>
                <a href="#" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">
                  shop now
                  <svg width="24" height="24" viewBox="0 0 24 24" class="mb-1">
                    <use xlink:href="#arrow-right"></use>
                  </svg></a>
              </div>

            </div>
          </div>
          
        </div>

        <div class="swiper-pagination mb-5"></div>

      </div>
    </div>
  </section>

  

  

  <?php 
    $objCategory = new Category();
    $objProduct = new Workshop();
    
    $categories = $objCategory->getCategory();

    $categoryProducts = [];
    foreach ($categories as $category) {
        $objProduct->setCId($category['ID']);

        $productsQuery = $objProduct->get4Products(); // Adjust limit as needed
        $categoryProducts[$category['ID']] = $productsQuery;
    }
    
  ?>
  <section id="products" class="my-5">
    <div class="container my-5 py-5">
        <div class="section-header d-md-flex justify-content-between align-items-center">
            <h2 class="display-3 fw-normal">Product</h2>
            <div class="mb-4 mb-md-0">
                <p class="m-0">
                    <button class="filter-button me-4 active" data-filter="*">ALL</button>
                    <?php foreach ($categories as $category) { ?>
                        <button class="filter-button me-4" data-filter=".cat-<?php echo $category['ID']; ?>">
                            <?php echo strtoupper($category['Name']); ?>
                        </button>
                    <?php } ?>
                </p>
            </div>
            
        </div>

        <div class="isotope-container row">
            <?php foreach ($categoryProducts as $categoryID => $products) { ?>
                <?php foreach ($products as $product) { ?>
                    <div class="item cat-<?php echo $categoryID; ?> col-md-4 col-lg-3 my-4">
                        <div class="card position-relative shadow-sm border">
                            <a href="single-product.php?id=<?php echo $product['ID']; ?>">
                                <img src="../images/Category/<?php echo $product['Image']; ?>" class="img-fluid rounded-4" alt="<?php echo $product['ProductName']; ?>">
                            </a>
                            <div class="card-body p-0">
                                <a href="single-product.php?id=<?php echo $product['ID']; ?>">
                                    <h3 class="card-title pt-4 m-0"><?php echo $product['ProductName']; ?></h3>
                                </a>
                                <div class="card-text">
                                    <span class="rating secondary-font">
                                        <!-- Example static rating, replace with dynamic if available -->
                                        <iconify-icon icon="clarity:star-solid" class="text-primary"></iconify-icon>
                                        <iconify-icon icon="clarity:star-solid" class="text-primary"></iconify-icon>
                                        <iconify-icon icon="clarity:star-solid" class="text-primary"></iconify-icon>
                                        <iconify-icon icon="clarity:star-solid" class="text-primary"></iconify-icon>
                                        <iconify-icon icon="clarity:star-solid" class="text-primary"></iconify-icon>
                                        5.0
                                    </span>
                                    <h3 class="secondary-font text-primary">$<?php echo number_format($product['Price'], 2); ?></h3>
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
                                          <!-- <a href="#" class="btn-wishlist px-4 pt-3 ">
                                            <iconify-icon icon="fluent:heart-28-filled" class="fs-5"></iconify-icon>
                                          </a> -->
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</section>

<?php
    $bestSellingProducts = $objProduct->GetTopSell();
?>

 <section id="bestselling" class="my-5 overflow-hidden">
    <div class="container py-5 mb-5">

        <div class="section-header d-md-flex justify-content-between align-items-center mb-3">
            <h2 class="display-3 fw-normal">Best Selling Products</h2>
            <div>
                <a href="#" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">
                    Shop Now
                    <svg width="24" height="24" viewBox="0 0 24 24" class="mb-1">
                        <use xlink:href="#arrow-right"></use>
                    </svg>
                </a>
            </div>
        </div>

        <div class="swiper bestselling-swiper">
            <div class="swiper-wrapper">

                <?php foreach ($bestSellingProducts as $product): ?>
                    <div class="swiper-slide">
                        <div class="card position-relative shadow-sm border ">
                            <a href="single-product.php?id=<?php echo $product['ID']; ?>">
                                <img src="../images/Category/<?php echo $product['Image']; ?>" class="img-fluid rounded-4" alt="image">
                            </a>
                            <div class="card-body p-0">
                                <a href="single-product.php?id=<?php echo $product['ID']; ?>">
                                    <h3 class="card-title pt-4 m-0"><?php echo $product['ProductName']; ?></h3>
                                </a>

                                <div class="card-text">
                                    <h3 class="secondary-font text-primary">$<?php echo number_format($product['Price'], 2); ?></h3>
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
                                          <!-- <a href="#" class="btn-wishlist px-4 pt-3 ">
                                            <iconify-icon icon="fluent:heart-28-filled" class="fs-5"></iconify-icon>
                                          </a> -->
                                        </div>
                                    <?php }?>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
        <!-- / category-carousel -->

    </div>
</section>

<!--   <section id="register" style="background: url('../images/background-img.png') no-repeat;">
    <div class="container ">
      <div class="row my-5 py-5">
        <div class="offset-md-3 col-md-6 my-5 ">
          <h2 class="display-3 fw-normal text-center">Get 20% Off on <span class="text-primary">first Purchase</span>
          </h2>
          <form>
            <div class="mb-3">
              <input type="email" class="form-control form-control-lg" name="email" id="email"
                placeholder="Enter Your Email Address">
            </div>
            <div class="mb-3">
              <input type="password" class="form-control form-control-lg" name="email" id="password1"
                placeholder="Create Password">
            </div>
            <div class="mb-3">
              <input type="password" class="form-control form-control-lg" name="email" id="password2"
                placeholder="Repeat Password">
            </div>

            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-dark btn-lg rounded-1">Register it now</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section> -->



  <section id="service">
    <div class="container py-5 my-5">
      <div class="row g-md-5 pt-4">
       <div class="col-md-4 my-4"></div>
        <div class="col-md-4 my-4">
          <div class="card">
            <div>
              <iconify-icon class="service-icon text-primary" icon="la:user-check"></iconify-icon>
            </div>
            <h3 class="card-title py-2 m-0">100% secure payment</h3>
            <div class="card-text">
              <p class="blog-paragraph fs-6"></p>
            </div>
          </div>
        </div>
        <!-- 
        <div class="col-md-4 my-4">
          <div class="card">
            <div>
              <iconify-icon class="service-icon text-primary" icon="la:tag"></iconify-icon>
            </div>
            <h3 class="card-title py-2 m-0">Daily Offer</h3>
            <div class="card-text">
              <p class="blog-paragraph fs-6"></p>
            </div>
          </div>
        </div>
        <div class="col-md-4 my-4">
          <div class="card">
            <div>
              <iconify-icon class="service-icon text-primary" icon="la:award"></iconify-icon>
            </div>
            <h3 class="card-title py-2 m-0">Quality guarantee</h3>
            <div class="card-text">
              <p class="blog-paragraph fs-6"></p>
            </div>
          </div>
        </div> -->

      </div>
    </div>
  </section>



  <?php include_once("../hf/footer.php"); ?>
<?php $title = "Single Product";?>
<?php include_once("../hf/header.php");?>
<?php require_once("../classes/workshop.class.php");?>
<?php require_once("../classes/cart.class.php");?>

<?php
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $objproduct = new workshop();
    $objproduct->setId($product_id);
    $product = $objproduct->getProduct();
    if ($product === null) {
        echo "<h1>Product not found</h1>";
        exit();
    }
} else {
    echo "<h1>No product ID provided</h1>";
    exit();
}
?>

<section id="banner" class="py-3" style="background: #F9F3EC;">
    <div class="container">
        <div class="hero-content py-5 my-3">
            <h2 class="display-1 mt-3 mb-0"> <span class="text-primary"><?php echo htmlspecialchars($product['ProductName']); ?></span></h2>
            <nav class="breadcrumb">
                <a class="breadcrumb-item nav-link" href="#">Home</a>
                <a class="breadcrumb-item nav-link" href="./">Pages</a>
                <span class="breadcrumb-item active" aria-current="page">Single-Product</span>
            </nav>
        </div>
    </div>
</section>

<section id="selling-product">
    <div class="container my-md-5 py-5">
        <div class="row g-md-5">
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-md-12">
                        <!-- product-large-slider -->
                        <div class="swiper product-large-slider swiper-fade swiper-initialized swiper-horizontal swiper-watch-progress swiper-backface-hidden">
                            <div class="swiper-wrapper" id="swiper-wrapper-105c2f52100e8c6475" aria-live="polite">
                                <?php
                                $images = explode(',', $product['Image']);
                                foreach ($images as $image) {
                                    echo '<div class="swiper-slide swiper-slide-visible swiper-slide-active" role="group" aria-label="1 / 4">';
                                    echo '<img src="../images/Category/' . htmlspecialchars($image) . '" class="img-fluid">';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                    </div>
                    <!-- <div class="col-md-12 mt-2"> -->
                        <!-- product-thumbnail-slider -->
                        <!-- <div thumbsslider="" class="swiper product-thumbnail-slider swiper-initialized swiper-horizontal swiper-free-mode swiper-watch-progress swiper-backface-hidden swiper-thumbs">
                            <div class="swiper-wrapper" id="swiper-wrapper-ba702457cd9b4469" aria-live="polite">
                                <?php
                                foreach ($images as $image) {
                                    echo '<div class="swiper-slide swiper-slide-visible swiper-slide-active swiper-slide-thumb-active" role="group" aria-label="1 / 4">';
                                    echo '<img src="../images/Category/' . htmlspecialchars($image) . '" class="img-fluid">';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                    </div> -->
                </div>
            </div>
            <div class="col-lg-6 mt-5">
                <div class="product-info">
                    <div class="element-header">
                        <h2 itemprop="name" class="display-6"><?php echo htmlspecialchars($product['ProductName']); ?></h2>
                        <div class="rating-container d-flex gap-0 align-items-center">
                            <span class="rating secondary-font">
                                <iconify-icon icon="clarity:star-solid" class="text-primary"></iconify-icon>
                                <iconify-icon icon="clarity:star-solid" class="text-primary"></iconify-icon>
                                <iconify-icon icon="clarity:star-solid" class="text-primary"></iconify-icon>
                                <iconify-icon icon="clarity:star-solid" class="text-primary"></iconify-icon>
                                <iconify-icon icon="clarity:star-solid" class="text-primary"></iconify-icon>
                                <!-- <?php echo htmlspecialchars($product['rating']); ?> -->
                            </span>
                        </div>
                    </div>
                    <div class="product-price pt-3 pb-3">
                        <strong class="text-primary display-6 fw-bold">$<?php echo htmlspecialchars($product['Price']); ?></strong>
                        <!-- <del class="ms-2">$<?php echo htmlspecialchars($product['old_price']); ?></del> -->
                    </div>
                    <!-- <p><?php echo htmlspecialchars($product['Description']); ?></p> -->
                    <div class="cart-wrap">
                        <div class="color-options product-select">
                            <div class="color-toggle pt-2" data-option-index="0">
                                <h6 class="item-title fw-bold">Color:</h6>
                                <ul class="select-list list-unstyled d-flex">
                                    <?php
                                    $colors = explode(',', $product['Color']);
                                    foreach ($colors as $color) {
                                        echo '<li class="select-item pe-3" data-val="'.htmlspecialchars($color).'" title="'.htmlspecialchars($color).'">';
                                        echo '<a href="#" class="btn btn-light">'.htmlspecialchars($color).'</a>';
                                        echo '</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <div class="swatch product-select pt-3" data-option-index="1">
                            <h6 class="item-title fw-bold" data-sider-select-id="9cd9bbfb-a1dc-4ca7-a577-e29f852e547f">Size:</h6>
                            <ul class="select-list list-unstyled d-flex">
                                <?php
                                $sizes = explode(',', $product['Size']);
                                foreach ($sizes as $size) {
                                    echo '<li data-value="'.htmlspecialchars($size).'" class="select-item pe-3">';
                                    echo '<a href="#" class="btn btn-light">'.htmlspecialchars($size).'</a>';
                                    echo '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="product-quantity pt-2">
                            <div class="stock-number text-dark"><em><?php echo htmlspecialchars($product['stock_quantity']); ?> in stock</em></div>
                            <div class="stock-button-wrap">
                                <?php if(isset($_SESSION['customer'])){?>
                                    <div class="d-flex flex-wrap pt-4">
                                        <?php
                                        
                                        $objCart = new cart();
                                        $objCart->setCid($_SESSION['customer']);
                                          $cartItems = $objCart->getAllCartItems();
                                          $cartProductIds = array_column($cartItems, 'Product');
                                          $disabled = "";
                                          if (in_array($product['ID'], $cartProductIds)) {
                                              $disabled = "disabled";
                                          }
                                        ?>
                                        <button id="cartBtn_<?= $product['ID'];?>" role="button" class="btn-cart me-3 px-4 pt-3 pb-3" onclick="addToCart(<?= $_GET['id'];?>,this.id);" <?php echo $disabled;?>>
                                          <h5 class="text-uppercase m-0" >Add to Cart</h5>
                                        </button><!-- 
                                        <a href="#" class="btn-wishlist px-4 pt-3">
                                            <iconify-icon icon="fluent:heart-28-filled" class="fs-5"></iconify-icon>
                                        </a> -->
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                    <div class="meta-product pt-4">
                        <!-- <div class="meta-item d-flex align-items-baseline">
                            <h6 class="item-title fw-bold no-margin pe-2">SKU:</h6>
                            <ul class="select-list list-unstyled d-flex">
                                <li data-value="S" class="select-item">.</li>
                            </ul>
                        </div> -->
                        <div class="meta-item d-flex align-items-baseline">
                            <h6 class="item-title fw-bold no-margin pe-2">Category:</h6>
                            <ul class="select-list list-unstyled d-flex">
                                <?php
                                $categories = explode(',', $product['category_name']);
                                foreach ($categories as $category) {
                                    echo '<li data-value="'.htmlspecialchars($category).'" class="select-item">';
                                    echo '<a>'.htmlspecialchars($category).'</a>';
                                    echo ',</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <!-- <div class="meta-item d-flex align-items-baseline">
                            <h6 class="item-title fw-bold no-margin pe-2">Tags:</h6>
                            <ul class="select-list list-unstyled d-flex">
                                <?php
                                $tags = explode(',', $product['tags']);
                                foreach ($tags as $tag) {
                                    echo '<li data-value="'.htmlspecialchars($tag).'" class="select-item">';
                                    echo '<a href="#">'.htmlspecialchars($tag).'</a>';
                                    echo ',</li>';
                                }
                                ?>
                            </ul>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="product-description bg-grey pt-5 pb-5">
    <div class="container">
        <div class="description-tab">
            <ul class="nav d-flex justify-content-center gap-2" role="tablist">
                <li class="nav-item">
                    <a class="nav-link text-uppercase active" data-bs-toggle="tab" href="#description" role="tab">Description</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link text-uppercase" data-bs-toggle="tab" href="#additional-info" role="tab">Additional Information</a>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link text-uppercase" data-bs-toggle="tab" href="#reviews" role="tab">Reviews</a>
                </li> -->
            </ul>
            <div class="tab-content pt-3">
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <p><?php echo htmlspecialchars($product['Description']); ?></p>
                </div>
                <!-- <div class="tab-pane fade" id="additional-info" role="tabpanel">
                    <ul class="list-unstyled">
                        <li><strong>Brand:</strong> <?php echo htmlspecialchars($product['brand']); ?></li>
                        <li><strong>Weight:</strong> <?php echo htmlspecialchars($product['weight']); ?></li>
                        <li><strong>Dimensions:</strong> <?php echo htmlspecialchars($product['dimensions']); ?></li>
                    </ul>
                </div> -->
                <!-- <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <p>Customer reviews will be displayed here.</p>
                </div> -->
            </div>
        </div>
    </div>
</section>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#add-to-cart').click(function(e) {
        e.preventDefault();
        var productId = <?php echo $product['id']; ?>;
        var quantity = parseInt($('#quantity').val());

        $.ajax({
            url: '../path/to/add_to_cart.php',
            type: 'POST',
            data: { product_id: productId, quantity: quantity },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    $('#cart-success').removeClass('d-none');
                    $('#cart-error').addClass('d-none');
                } else {
                    $('#cart-error').removeClass('d-none');
                    $('#cart-success').addClass('d-none');
                }
            },
            error: function() {
                $('#cart-error').removeClass('d-none');
                $('#cart-success').addClass('d-none');
            }
        });
    });
});
</script> -->

<?php include_once("../hf/footer.php");?>
